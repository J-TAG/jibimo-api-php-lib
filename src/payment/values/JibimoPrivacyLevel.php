<?php


namespace puresoft\jibimo\payment\values;


abstract class JibimoPrivacyLevel
{
    // Jibimo privacy levels for transactions

    /**
     * It means the transaction is only visible between two parties that are involved in it,
     * meaning payer and payee. So only these two people can see this transaction.
     */
    const PERSONAL = 'Personal'; //

    /**
     * It means the transaction is only visible between two parties that are involved in it *AND* their friends,
     * meaning payer and payee and Jibimo friends of payer and Jibimo friends of payee.
     * In this privacy level, the amount of transaction is not visible for people other than payer and payee.
     */
    const FRIEND = 'Friend';

    /**
     * Means anyone who is registered in Jibimo can see this transaction.
     * So it can be a good point for promoting your products in a social media like, type of feed.
     * In this privacy level, the amount of transaction is not visible for people other than payer and payee.
     */
    const PUBLIC = 'Public';
}