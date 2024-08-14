<?php

namespace Eloise\DataAudit\Tests\Unit\MainFeatures;

use Eloise\DataAudit\Constants\Actions;
use Eloise\DataAudit\Constants\AuditableProperties;
use Eloise\DataAudit\Models\Audit;
use Eloise\DataAudit\Tests\Fixtures\Models\DefaultAuditableModel;
use Eloise\DataAudit\Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DefaultModelOperationTest extends TestCase
{

    public function test_audits_on_creating_models()
    {
        $user = $this->createTestUser();

        $this->actingAs($user);

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);

        // Creating DefaultAuditableModels
        $randomNumber = rand(1,20);
        $fakeNames = $this->createTestAuditableModels($randomNumber);

        $this->assertEquals($randomNumber, DefaultAuditableModel::count());

        foreach($fakeNames as $fakeName) {
            $this->assertDatabaseHas('test_eloise_auditable_model', ['test_name' => $fakeName]);
        }

        // Checking the Audits created
        $this->assertEquals($randomNumber, Audit::count());
        foreach($fakeNames as $fakeName) {
            $model = DefaultAuditableModel::where(['test_name' => $fakeName])->first();
            $audits = Audit::where(
                [
                    'user_id' => $user->id,
                    'source_id' => $model->id
                ])->get();
            $this->assertEquals(1,$audits->count());
            $audit = $audits->first();
            $this->assertEquals(Actions::ACTION_CREATED,$audit->action);
            $this->assertEquals($model->getSourceModelClass(),$audit->source_class);
        }
    }

    public function test_audits_on_updating_models()
    {
        $user = $this->createTestUser();

        $this->actingAs($user);

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);

        // Creating DefaultAuditableModels
        $numberOfCreatedModels = rand(10,20);
        $numberOfUpdatedModels = rand(1,$numberOfCreatedModels);

        $fakeNames = $this->createTestAuditableModels($numberOfCreatedModels);
        $randomFakeNames = $this->getSomeRandomFakeNames($fakeNames, $numberOfUpdatedModels);

        foreach($randomFakeNames as $randomFakeName) {
            $model = DefaultAuditableModel::where(['test_name' => $randomFakeName])->first();
            $model->update(['test_name' => $randomFakeName.' updated']);
        }

        $totalOfAudits = $numberOfCreatedModels + $numberOfUpdatedModels;
        $this->assertEquals($totalOfAudits, Audit::count());
        
        // Checking the Audits updated
        foreach($randomFakeNames as $randomFakeName) {
            $model = DefaultAuditableModel::where(['test_name' => $randomFakeName.' updated'])->first();
            $audits = Audit::where(
                [
                    'user_id' => $user->id,
                    'source_id' => $model->id
                ])->get();

            $this->assertEquals(2,$audits->count());

            $auditCreated = $audits->firstWhere('action', Actions::ACTION_CREATED);
            $this->assertNotEmpty($auditCreated);
            $this->assertEquals($model->getSourceModelClass(),$auditCreated->source_class);

            $auditUpdated = $audits->firstWhere('action', Actions::ACTION_UPDATED);
            $this->assertNotEmpty($auditUpdated);
            $this->assertEquals($model->getSourceModelClass(),$auditUpdated->source_class);

            // Checking the changes property everything has been saved correctly
            $createdChanges = $this->getSpecificAttribute($auditCreated->changes, 'test_name');
            $updatedChanges = $this->getSpecificAttribute($auditUpdated->changes, 'test_name');

            $this->assertEquals($createdChanges[AuditableProperties::NEW_VALUE],$updatedChanges[AuditableProperties::ORIGINAL_VALUE]);
            $this->assertEquals($createdChanges[AuditableProperties::NEW_VALUE] . ' updated', $updatedChanges[AuditableProperties::NEW_VALUE]);
        }
    }

    public function createTestUser(): User
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'testuser@example.com';
        $user->password = Hash::make('password');
        $user->save();

        return $user;
    }

    public function createTestAuditableModels($randomNumber): array
    {
        $faker = Faker::create();
        $fakeNames = [];
        for ($i = 0; $i < $randomNumber; $i++) {
            $fakeNames[$i]= $faker->name;
            DefaultAuditableModel::create([
                'test_name' => $fakeNames[$i],
            ]);
        }

        return $fakeNames;
    }

    public function getSomeRandomFakeNames(array $fakeNames, int $randomNumber): array
    {
        $randomKeys = array_rand($fakeNames, $randomNumber);
        
        // Ensure $randomKeys is always an array (if $randomNumber is 1 then $randomKeys will be an integer insted of an array)
        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randomNames = array_map(function($key) use ($fakeNames) {
                return $fakeNames[$key];
            }, $randomKeys);

        return $randomNames;
    }

    public function getSpecificAttribute(array $changes, string $attribute): array
    {
        $createdValue=[];
        foreach($changes as $value) {
            if (isset($value[$attribute])) {
                $createdValue = $value[$attribute];
            }
        }

        return $createdValue;
        }
}
