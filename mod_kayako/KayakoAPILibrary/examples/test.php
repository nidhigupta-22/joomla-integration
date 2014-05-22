<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rahulbhattacharya
 * Date: 10/17/12
 * Time: 3:45 PM
 * To change this template use File | Settings | File Templates.
 */

require_once("../kyIncludes.php");

kyConfig::set(new kyConfig("http://localhost/trunk/api/index.php?", "51ec490b-e636-aff4-b96c-ae9e746656db", "OWU3YjYwZGItNmQ4NS01NTI0LTk5YjEtMzc2MjFjNzY1ZmFmMWFmNzVjMmMtYzA1NS1kOWY0LTE5MDgtZmJiYWE5MzllMDg4"));
kyConfig::get()->setDebugEnabled(true);



$tickets = kyTicket::getAll(
    kyUser::getAll()
        ->filterByEmail("librianrahul@gmail.com")
);


print $tickets;