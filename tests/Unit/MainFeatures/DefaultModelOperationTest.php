<?php

namespace Eloise\RecordModel\Tests\Unit\MainFeatures;

use Eloise\RecordModel\Constants\Actions;
use Eloise\RecordModel\Constants\RecordableProperties;
use Eloise\RecordModel\Models\Record;
use Eloise\RecordModel\Tests\Fixtures\Models\DefaultRecordableModel;
use Eloise\RecordModel\Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DefaultModelOperationTest extends TestCase
{
    public function test_records_on_creating_models()
    {
        $user = $this->createTestUser();

        $this->actingAs($user);

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);

        // Creating DefaultRecordableModels
        $randomNumber = rand(1, 20);
        $fakeNames = $this->createTestRecordableModels($randomNumber);

        $this->assertEquals($randomNumber, DefaultRecordableModel::count());

        foreach ($fakeNames as $fakeName) {
            $this->assertDatabaseHas('test_eloise_recordable_model', ['test_name' => $fakeName]);
        }

        // Checking the Records created
        $this->assertEquals($randomNumber, Record::count());
        foreach ($fakeNames as $fakeName) {
            $model = DefaultRecordableModel::where(['test_name' => $fakeName])->first();
            $records = Record::where(
                [
                    'user_id' => $user->id,
                    'source_id' => $model->id
                ]
            )->get();
            $this->assertEquals(1, $records->count());
            $record = $records->first();
            $this->assertEquals(Actions::ACTION_CREATED, $record->action);
            $this->assertEquals($model->getSourceModelClass(), $record->source_class);
        }
    }

    public function test_records_on_updating_models()
    {
        $user = $this->createTestUser();

        $this->actingAs($user);

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);

        // Creating DefaultRecordableModels
        $numberOfCreatedModels = rand(10, 20);
        $numberOfUpdatedModels = rand(1, $numberOfCreatedModels);

        $fakeNames = $this->createTestRecordableModels($numberOfCreatedModels);
        $randomFakeNames = $this->getSomeRandomFakeNames($fakeNames, $numberOfUpdatedModels);

        foreach ($randomFakeNames as $randomFakeName) {
            $model = DefaultRecordableModel::where(['test_name' => $randomFakeName])->first();
            $model->update(['test_name' => $randomFakeName . ' updated']);
        }

        $totalOfRecords = $numberOfCreatedModels + $numberOfUpdatedModels;
        $this->assertEquals($totalOfRecords, Record::count());

        // Checking the Records updated
        foreach ($randomFakeNames as $randomFakeName) {
            $model = DefaultRecordableModel::where(['test_name' => $randomFakeName . ' updated'])->first();
            $records = Record::where(
                [
                    'user_id' => $user->id,
                    'source_id' => $model->id
                ]
            )->get();

            $this->assertEquals(2, $records->count());

            $recordCreated = $records->firstWhere('action', Actions::ACTION_CREATED);
            $this->assertNotEmpty($recordCreated);
            $this->assertEquals($model->getSourceModelClass(), $recordCreated->source_class);

            $recordUpdated = $records->firstWhere('action', Actions::ACTION_UPDATED);
            $this->assertNotEmpty($recordUpdated);
            $this->assertEquals($model->getSourceModelClass(), $recordUpdated->source_class);

            // Checking the diff property everything has been saved correctly
            $createdChanges = $this->getSpecificAttribute($recordCreated->diff, 'test_name');
            $updatedChanges = $this->getSpecificAttribute($recordUpdated->diff, 'test_name');

            $this->assertEquals($createdChanges[RecordableProperties::NEW_VALUE], $updatedChanges[RecordableProperties::ORIGINAL_VALUE]);
            $this->assertEquals($createdChanges[RecordableProperties::NEW_VALUE] . ' updated', $updatedChanges[RecordableProperties::NEW_VALUE]);
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

    public function createTestRecordableModels($randomNumber): array
    {
        $faker = Faker::create();
        $fakeNames = [];
        for ($i = 0; $i < $randomNumber; $i++) {
            $fakeNames[$i] = $faker->name;
            DefaultRecordableModel::create([
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

        $randomNames = array_map(function ($key) use ($fakeNames) {
                return $fakeNames[$key];
        }, $randomKeys);

        return $randomNames;
    }

    public function getSpecificAttribute(array $diff, string $attribute): array|null
    {
        foreach ($diff as $value) {
            if ($value[RecordableProperties::FIELD]==$attribute) {
                return $value;
            }
        }

        return null;
    }
}
