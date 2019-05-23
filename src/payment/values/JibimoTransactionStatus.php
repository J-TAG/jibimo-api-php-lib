<?php


namespace puresoft\jibimo\payment\values;


abstract class JibimoTransactionStatus
{
    // Jibimo transaction statuses

    const REJECTED = 'Rejected';
    const PENDING = 'Pending';
    const ACCEPTED = 'Accepted';
}