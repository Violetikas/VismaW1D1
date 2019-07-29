<?php 

class APICest
{
    const FIND_IN_WORDS = 'mistranslate';

    public function _before(ApiTester $I)
    {
    }

    // tests
    public function  getWordsViaAPI(ApiTester $I)
    {
        $I->wantToTest('Getting words');

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/words');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'word' => self::FIND_IN_WORDS
        ]);
    }
    public function insertWordViaAPI(ApiTester $I)
    {
        $I->wantToTest('Insertion of word');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/words', ['word'=>'apple']);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
       ;
    }
    public function tryDeleteWord(ApiTester $I)
    {
        $I->wantToTest('Deletion of word');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/words/apple');
        $I->seeResponseCodeIs(200);
    }

    public function updateWord(ApiTester $I){
        $I->wantToTest('Updating word');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/words/Gediminas');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'message' => 'Word updated'
        ]);

    }
}
