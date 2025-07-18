<?php

namespace wm\yii\helpers;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;

//TODO Сделать метод чище - убрать параметр $admins в замен что то типа true/false
//TODO Так как мы получаем все данные о пользователе то и выводить стоит все
//    $employeeName['id'] = (int)$request[0]['ID'];
//    $employeeName['title'] = $request[0]['LAST_NAME'] . ' ' . $request[0]['NAME'];
//    return [$employeeName];

class B24Helper
{
    /**
     * @param int $b24UserId
     * @param int[] $adminUsers
     * @return mixed[]
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function getSubUsers($b24UserId, $adminUsers = [])
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);
        if (array_search($b24UserId, $adminUsers) === false) {
            $b24 = $obB24->client->call('department.get', ['UF_HEAD' => $b24UserId]);
            switch ($b24['total']) {
                case 0:
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $request = $obB24->client->call('user.get', [
                            'filter' => ['ID' => $b24UserId],
                            'select' => ['ID', 'NAME', 'LAST_NAME']
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   ])['result'];
                            $employeeName['id'] = (int)$request[0]['ID'];
                            $employeeName['title'] = $request[0]['LAST_NAME'] . ' ' . $request[0]['NAME'];

                    return [$employeeName];
                default:
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $b24Res = $b24['result'];
                    $departmentIds = ArrayHelper::getColumn($b24Res, 'ID');
                    $departments = $obB24->client->call('department.get', [])['result'];
                    $resDepartmentIds = [];
                    foreach ($departmentIds as $departmentId) {
                        $resDepartmentIds = array_merge($resDepartmentIds, self::getSubDepartments($departmentId, $departments));
                    }
                    $resUniqueDepartmentIds = array_unique($resDepartmentIds);
                    $b24Users = self::getUsersByDepartmentIds($resUniqueDepartmentIds);
                    $mas = [];
                    foreach ($b24Users as $b24User) {
                        $mas1['id'] = ArrayHelper::getValue($b24User, 'ID');
                        $mas1['title'] = ArrayHelper::getValue($b24User, 'LAST_NAME') . ' ' . ArrayHelper::getValue($b24User, 'NAME');
                        $mas[] = $mas1;
                    }

                    return $mas;
            }
        } else {
            $b24Users = $obB24->client->call('user.get', [
                    'filter' => ['ACTIVE' => true]
                ]);
            $countCalls = (int)ceil($b24Users['total'] / $obB24->client::MAX_BATCH_CALLS);
            $res = $b24Users['result'];
            for ($i = 1; $i < $countCalls; $i++) {
                $obB24->client->addBatchCall(
                    'user.get',
                    [
                        'filter' => ['ACTIVE' => true],
                        'start' => $obB24->client::MAX_BATCH_CALLS * $i
                    ],
                    function ($result) use (&$res) {

                        $res = array_merge($res, $result['result']);
                    }
                );
            }
            $obB24->client->processBatchCalls();
            $mas = [];
            foreach ($res as $b24User) {
                $mas1['id'] = (int)ArrayHelper::getValue($b24User, 'ID');
                $mas1['title'] = ArrayHelper::getValue($b24User, 'LAST_NAME') . ' ' . ArrayHelper::getValue($b24User, 'NAME');
                $mas[] = $mas1;
            }
            return $mas;
        }
    }

    public static function getUsersByDepartmentIds($departmentIds)
    {

        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $request = $obB24->client->call('user.get', [
                'filter' => [
                    'ACTIVE' => true,
                    'UF_DEPARTMENT' => $departmentIds,
                ],
            ]);
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
        $res = $request['result'];
        for ($i = 1; $i < $countCalls; $i++) {
            $obB24->client->addBatchCall(
                'user.get',
                [
                    'filter' => [
                        'ACTIVE' => true,
                        'UF_DEPARTMENT' => $departmentIds,
                    ],
                    'start' => $obB24->client::MAX_BATCH_CALLS * $i
                ],
                function ($result) use (&$res) {

                    $res = array_merge($res, $result['result']);
                }
            );
        }
        $obB24->client->processBatchCalls();
        return $res;
    }

    private static function getSubDepartments($departmentId, $departments)
    {
        $res = [$departmentId];
        foreach ($departments as $department) {
            if (ArrayHelper::getValue($department, 'PARENT') == $departmentId) {
                $res[] = ArrayHelper::getValue($department, 'ID');
                $res = array_merge($res, self::getSubDepartments($department['ID'], $departments));
            }
        }
        return $res;
    }

    public static function getEmployeesList($users, $obB24)
    {
        $employees = $obB24->client->call('user.get', [$users])['result'];
        $res = [];
        foreach ($employees as $employee) {
            $name = ArrayHelper::getValue($employee, 'NAME');
            $lastName = ArrayHelper::getValue($employee, 'LAST_NAME');
            $empl = $name . ' ' . $lastName;
            $res[] = $empl;
        }
        return $res;
    }
}
