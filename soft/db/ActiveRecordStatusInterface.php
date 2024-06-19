<?php

namespace soft\db;

interface ActiveRecordStatusInterface
{

    /**
     * O'chirilgan
     */
    const STATUS_DELETED = -1;

    /**
     * Nofaol
     */
    const STATUS_INACTIVE = 0;

    /**
     * Faol
     */
    const STATUS_ACTIVE = 1;

    /**
     * Bekor qilindi
     */
    const STATUS_CANCELED = 2;

    /**
     * Yangi
     */
    const STATUS_NEW = 5;

    /**
     * Ruxsat berildi
     */
    const STATUS_ALLOWED = 6;

    /**
     * Kutish rejimida
     */
    const STATUS_WAITING = 7;

    /**
     * Qabul qilindi
     */
    const STATUS_ACCEPTED = 8;

    /**
     * Ko'rildi
     */
    const STATUS_VIEWED = 9;

    /**
     * Jarayonda
     */
    const STATUS_IN_PROCESS = 11;

    /**
     * To'grilandi
     */
    const STATUS_FIXED = 12;


}
