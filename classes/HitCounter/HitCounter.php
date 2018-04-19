<?php

namespace HitCounter;

use Util\DBAccess;
use Util\Request;
use Util\Utility;

class HitCounter {

    const SESSION_TIMEOUT_IN_MINUTE = 5;

    private $totalOnline = 0;

    private $totalCounter = 0;

    private $dbAccess;

    public function __construct()
    {
        $this->dbAccess = new DBAccess('hit_counter', 'root', '123456');
    }

    public function trackingVisit()
    {
        $request = new Request();

        $sessionId = $request->getSessionId();
        $ip = Utility::getIP();
        $os = Utility::getOS();
        $device = Utility::getBrowser();
        $lang = Utility::getLang();

        $sql = "
            SELECT COUNT(1)
            FROM visitors
            WHERE session_id = '{$sessionId}'
            AND ip = '{$ip}'
            AND last_visit >= UNIX_TIMESTAMP() - ".static::SESSION_TIMEOUT_IN_MINUTE."*60";
        $check = $this->dbAccess->scalarBySQL($sql);

        if ($check == 0) {
            $this->dbAccess->save('visitors', array(
                'session_id' => $sessionId,
                'ip' => $ip,
                'last_visit' => time(),
                'os' => $os,
                'device' => $device,
                'language' => $lang,
                'created_at' => 'NOW()',
            ));
        }
    }

    public function getOnlineNow()
    {
        $sql = "
            SELECT COUNT(1)
            FROM visitors
            WHERE last_visit >= UNIX_TIMESTAMP() - ".static::SESSION_TIMEOUT_IN_MINUTE."*60";
        $total = $this->dbAccess->scalarBySQL($sql);

        return $total;
    }

    public function getVisitToday()
    {
        $sql = "
            SELECT COUNT(1)
            FROM visitors
            WHERE DATE_FORMAT(created_at, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')";
        $total = $this->dbAccess->scalarBySQL($sql);

        return $total;
    }

    public function getVisitYesterday()
    {
        $sql = "SELECT total_view  FROM visitor_summary WHERE visit_date = SUBDATE(CURRENT_DATE, 1)";
        $total = $this->dbAccess->scalarBySQL($sql);

        return $total;
    }

    public function getTotalVisit()
    {
        $sql = "SELECT SUM(total_view) AS totalView  FROM visitor_summary";
        $total = $this->dbAccess->scalarBySQL($sql);

        return $total + $this->getVisitToday();
    }

    public function getVisited($limit)
    {
        $sql = "SELECT visit_date, total_view  FROM visitor_summary ORDER BY visit_date DESC LIMIT 0,{$limit}";

        $list = $this->dbAccess->findAllBySql($sql);
        $current = new \stdClass();
        $current->visit_date = date('Y-m-d');
        $current->total_view = $this->getVisitToday();
        array_unshift($list, $current);

        return $list;
    }

    public function calculateSummary()
    {
        $sql = "
        INSERT INTO visitor_summary (`visit_date`, `total_view`)
        SELECT
            DATE_FORMAT(created_at, '%Y-%m-%d') AS visitDate,
            COUNT(1) AS totalView
        FROM
            visitors
        WHERE
            DATE_FORMAT(created_at, '%Y-%m-%d') NOT IN
            (
                SELECT visit_date FROM visitor_summary
            )
        GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
        HAVING visitDate != DATE_FORMAT(NOW(), '%Y-%m-%d')";

        $this->dbAccess->query($sql);
    }

}
