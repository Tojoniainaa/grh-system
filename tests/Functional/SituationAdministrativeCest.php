<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;

final class SituationAdministrativeCest
{
    public function _before(FunctionalTester $I): void
    {
        // Code here will be executed before each test function.
    }

    // All `public` methods will be executed as tests.
    public function testPageSituationAdministrative(FunctionalTester $I): void
    {
        $I->amOnPage('/situation-administrative');

        $I->seeResponseCodeIs(200);

        $I->see('Situation Administrative');
    }
    public function testCreateSituationAdministrative(FunctionalTester $I): void
    {
        $I->amOnPage('/situation-administrative');


        $I->submitForm('form', [
            'Matricule' => 'TEST001',
            'StatusAgent' => 'FONC',
            'Code_Corps' => 'CORPS001',
            'categorie' => 1,
            'Indice' => 100,
            'Ref_Notification' => 'NOT001',
            'Imputation_Budgetaire' => 'BUD001',
            'Type_Contrat' => 'CDI',
            'Mode_Paie' => 'VIREMENT',
            'Code_Paie' => '001',
            'Code_Banque' => '001',
            'Numero_Compte' => '123456',
            'Code_Gradefonc' => 'GRADE001',
        ]);


        $I->see('Situation administrative ajoutée avec succès');
    }

}
