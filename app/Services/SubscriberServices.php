<?php

namespace App\Services;

use App\Models\Subscriber;
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


    /**
     * Обновить все подписки
     *
     * @param $subscribers
     * @param $list
     */
    public function syncSubscriptionStatus($subscribers, $list)
    {
            try{
                $subStatus = [];

                foreach($subscribers as $subscriber){
                    $subStatus[] = [
                        'email' => $subscriber->email,
                        'subscription_status' => Newsletter::isSubcree($subscriber->email),
                    ];
                }

                self::updatedStatuses($subStatus);

            }catch(\Exception $e){

            }
    }


    /**
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $email
     */
    public function addNewSubscriber($first_name, $last_name, $email)
    {
        $list = 'subscribers';

        $subscriber = new Subscriber();
        $subscriber->first_name = $first_name;
        $subscriber->last_name = $last_name;
        $subscriber->email = $email;
        $subscriber->subscription_status = true;
        $subscriber->save();

        Newsletter::subscribe(
            $email,
            [
                'FNAME' => $first_name,
                'LNAME' => $last_name
            ],
            $list
        );

        return $subscriber;

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
