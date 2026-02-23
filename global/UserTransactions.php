<?php
date_default_timezone_set('Asia/Manila');
class UserTransactions
{

    private $con;
    public function __construct($con)
    {
        $this->con = $con;
    }


    // Method to execute an INSERT, UPDATE, DELETE query
    public function insertTransaction($refrence, $actions, $status, $activity, $processor, $module)
    {


        $uniqueId = uniqid('', true); // Generate unique ID
        $uniqueIdHash = md5($uniqueId); // Hash the unique ID using md5
        $finalUniqueId = substr($uniqueIdHash, 0, 8) . '-' . substr($uniqueIdHash, 8, 4) . '-' . substr($uniqueIdHash, 12, 4) . '-' . substr($uniqueIdHash, 16, 4) . '-' . substr($uniqueIdHash, 20); // Format the unique ID as required



        $stmt = $this->con->prepare('INSERT INTO `user_transactions` (
                                                                            `usertransac_id`,
                                                                            `date`,
                                                                            `time`,
                                                                            `reference`,
                                                                            `actions`,
                                                                            `status`,
                                                                            `activity`,
                                                                            `processor`,
                                                                            `module`
                                                                            ) 
                                                                            VALUES
                                                                            (
                                                                            :usertransac_id,
                                                                            :date,
                                                                            :time,
                                                                            :reference,
                                                                            :actions,
                                                                            :status,
                                                                            :activity,
                                                                            :processor,
                                                                            :module
                                                                            )');

        $result = $stmt->execute([
            ':usertransac_id' => $finalUniqueId,
            ':date' => date('Y-m-d'),
            ':time' => date('H:i:s'),
            ':reference' => $refrence,
            ':actions' => $actions,
            ':status' => $status,
            ':activity' => $activity,
            ':processor' => $processor,
            ':module' => $module
        ]);
        return $result;
    }


    // Method to fetch data with optional parameters
    public function fetchTransaction($usertransac_id, $date, $time, $reference = null, $actions = null, $status = null, $activity = null, $processor = null, $module = null)
    {
        $conditions = [];
        $params = [];

        if (!empty($usertransac_id)) {
            $conditions[] = '`usertransac_id` = :usertransac_id';
            $params[':usertransac_id'] = $usertransac_id;
        }

        if (!empty($reference)) {
            $conditions[] = '`reference` = :reference';
            $params[':reference'] = $reference;
        }
        if (!empty($date)) {
            $conditions[] = '`date` = :date';
            $params[':date'] = $date;
        }

        if (!empty($time)) {
            $conditions[] = '`time` = :time';
            $params[':time'] = $time;
        }

        if (!empty($actions)) {
            $conditions[] = '`actions` = :actions';
            $params[':actions'] = $actions;
        }
        if (!empty($status)) {
            $conditions[] = '`status` = :status';
            $params[':status'] = $status;
        }
        if (!empty($activity)) {
            $conditions[] = '`activity` = :activity';
            $params[':activity'] = $activity;
        }
        if (!empty($processor)) {
            $conditions[] = '`processor` = :processor';
            $params[':processor'] = $processor;
        }
        if (!empty($module)) {
            $conditions[] = '`module` = :module';
            $params[':module'] = $module;
        }

        $sql = 'SELECT * FROM `user_transactions`';
        if (count($conditions) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
