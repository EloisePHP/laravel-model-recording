<?php

namespace Eloise\DataAudit\Tests\Unit\MainFeatures;

use Eloise\DataAudit\Contracts\AuditableModel;
use Eloise\DataAudit\Facades\Rollback;
use Eloise\DataAudit\Tests\Fixtures\Models\RollbackModel;
use Eloise\DataAudit\Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class RollbackFeatureTest extends TestCase
{
    public const int AMOUNT_OF_ROLLBACK_MODELS_TO_TEST = 1;

    public function testRollbackModel()
    {
        //First day
        Carbon::setTestNow(now());
        $faker = Faker::create();

        $user = $this->createTestUser();
        $this->actingAs($user);

        $this->createRollbackModels(self::AMOUNT_OF_ROLLBACK_MODELS_TO_TEST);

        //Second day
        Carbon::setTestNow(now()->addDay());
        $model = RollbackModel::find(1);
        $model->test_name = sprintf("%s %s", $model->test_name, $faker->name);
        $model->save();

        //Third day
        Carbon::setTestNow(now()->addDay());
        $model = RollbackModel::find(1);
        $model->test_int = $model->test_int + $faker->numberBetween(1, 100);

        $array = $model->test_array;
        array_push($array, $faker->word());
        $model->test_array = $array;

        $model->save();

        //Fourth day
        Carbon::setTestNow(now()->addDay());
        $audits = $model->audits();

        $this->assertCount(3, $audits);

        $this->rollbackComparer($model,null,false, false, false);
        $this->rollbackComparer($model,now()->subHours(60),false, false, false);
        $this->rollbackComparer($model,now()->subHours(36),true, false, false);
        $this->rollbackComparer($model,now()->subHours(24),true, true, true);        
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

    public function createRollbackModels($randomNumber): array
    {
        $faker = Faker::create();
        $fakeModels = [];

        for ($i = 0; $i < $randomNumber; $i++) {
            $fakeData = [
                'test_name' => $faker->name,
                'test_description' => $faker->sentence,
                'test_int' => $faker->numberBetween(1, 100),
                'test_array' => array_map(fn() => $faker->word, range(1, 5)),
            ];

            RollbackModel::create($fakeData);

            $fakeModels[] = $fakeData;
        }

        return $fakeModels;
    }

    public function rollbackComparer(
        AuditableModel $model,
        Carbon|null $momentToRollback,
        bool $nameEqual,
        bool $intEqual,
        bool $arrayEqual,
    ) {
        $rollbackModel = Rollback::forModel($model)
                                    ->atDate($momentToRollback)
                                    ->retrieve();
        $this->assertInstanceOf(RollbackModel::class, $rollbackModel);

        $comparisons = [
            'test_name' => $nameEqual,
            'test_int' => $intEqual,
            'test_array' => $arrayEqual,
        ];
    
        foreach ($comparisons as $property => $shouldEqual) {
            $this->compareProperty($model, $rollbackModel, $property, $shouldEqual);
        }
    }

    protected function compareProperty(
        AuditableModel $model,
        AuditableModel $rollbackModel,
        string $property,
        bool $shouldEqual
    ) {
        if ($shouldEqual) {
            $this->assertEquals($model->$property, $rollbackModel->$property, "Failed asserting that $property is equal.");
        } else {
            $this->assertNotEquals($model->$property, $rollbackModel->$property, "Failed asserting that $property is not equal.");
        }
    }
}