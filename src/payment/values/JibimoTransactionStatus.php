<?php


namespace puresoft\jibimo\payment\values;


abstract class JibimoTransactionStatus
{
    // Jibimo transaction statuses

    /**
     * Means one of parties were reject to accept the transaction or there is a problem with the transaction.
     */
    const REJECTED = 'Rejected';

    /**
     * This status means the transaction is pending for something else to happen.
     */
    const PENDING = 'Pending';

    /**
     * This status means that transaction was successful and everything went cool.
     */
    const ACCEPTED = 'Accepted';
}