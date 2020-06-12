<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Newsletter;

class SubscriberServices extends BaseService
{

    /**
     *
     *
     * @param $list
     */
    public function addSubscribersToList($subscribers, $list)
    {
        try {
            $subStatus = [];

            foreach ($subscribers as $subscriber) {
                Newsletter::subscribeOrUpdate(
                    $subscriber->email,
                    [
                        'FNAME' => $subscriber->first_name,
                        'LNAME' => $subscriber->last_name
                    ],
                    $list
                );

                $subStatus[] = [
                    'email' => $subscriber->email,
                    'subscription_status' => true
                ];

            }

            self::updatedStatuses($subStatus);

        } catch (\Exception $e) {
            dd($e);
        }


    }

    /**
     * Получить список всех подписчиков
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getSubscribers()
    {
        return DB::table('subscribers')
            ->get();
    }



    public function syncSubscriptionStatus($subscribers, $list)
    {
            try{
                $subStatus = [];

                foreach($subscribers as $subscriber){
                    $subStatus[] = [
                        'email' => $subscriber->email,
                        'subscription_status' => Newsletter::hasMember($subscriber->email),
                    ];
                }

                self::updatedStatuses($subStatus);

            }catch(\Exception $e){

            }
    }

    /**
     * Обновляем статусы
     *
     * @param array $statuses
     */
    protected function updatedStatuses(array $statuses)
    {
        DB::beginTransaction();
            foreach($statuses as $item){
                DB::table('subscribers')
                    ->where('email', '=' ,$item['email'])
                    ->update([
                       'sync_at' => now(),
                       'subscription_status' => $item['subscription_status']
                    ]);
            }
        DB::commit();

    }

}
