<?php


namespace puresoft\jibimo;


abstract class JibimoTransactionStatus
{
    // Jibimo transaction statuses
    const REJECTED = 'Rejected';
    const PENDING = 'Pending';
    const ACCEPTED = 'Accepted';
}