<?php

namespace common\modules\translation\models\sync;

use Yii;

class Sync
{


    public static function export()
    {

        $newLocalSourceMessages = SourceMessageLocal::newSourceMessagesOnLocal();


        foreach ($newLocalSourceMessages as $localSourceMessage){

            $hostSourceMessage = SourceMessageHost::findOne([
                'message' => $localSourceMessage->message,
            ]);

            $newRecord = false;
            if ($hostSourceMessage == null){

                static::saveNewLocalRecordToHosting($localSourceMessage);


                $newRecord = true;
            }


            if($hostSourceMessage->save()){



            }
            else{

                dump('Xatolik yuz berdi!');
                dd($hostSourceMessage->errors);

            }
        }

    }

    /**
     * @param $localNewSourceMessage SourceMessageLocal
     * @return bool
     */
    public static function saveNewLocalRecordToHosting($localNewSourceMessage)
    {
        $hostSourceMessage = new SourceMessageHost([
            'message' => $localNewSourceMessage->message,
        ]);
        $hostSourceMessage->category = 'app';
        $hostSourceMessage->created_at = $localNewSourceMessage->created_at;
        $hostSourceMessage->updated_at = $localNewSourceMessage->updated_at;
        if( $hostSourceMessage->save()){

            $messages = $localNewSourceMessage->messages;
            foreach ($messages as $message){

                $hostMessage = new  MessageHost();
                $hostMessage->language = $message->language;

            }

            return true;
        }
        else{

            dump('Local bazadagi yangi SourceMessageni hostingga saqlashda Xatolik yuz berdi!');
            dd($hostSourceMessage->errors);

        }
    }

    public static function latestLocalSourceMessageTime()
    {
        return SourceMessageLocal::find()->orderBy(['created_at' => SORT_DESC])->one()->created_at;
    }

    public static function latestHostSourceMessageTime()
    {
        return SourceMessageHost::find()->orderBy(['created_at' => SORT_DESC])->one()->created_at;
    }

}
