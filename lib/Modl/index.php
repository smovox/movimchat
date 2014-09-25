<?php
namespace modl;

include('loader.php');

loadNewData('Presence');
loadNewData('Contact');
loadNewData('Privacy');
loadNewData('RosterLink');

$db = Modl::getInstance();

$db->setConnectionArray(
    array(
        'dbType'    => 'mysql',
        'dbUsername'  => 'movim',
        'dbPassword'  => 'movim',
        'dbHost'      => 'localhost',
        'dbPort'      => 3306,
        'dbName'  => 'modl')
    );

$db->setConnectionArray(
    array(
        'dbType'    => 'pgsql',
        'dbUsername'  => 'postgres',
        'dbPassword'  => 'postgres',
        'dbHost'      => 'localhost',
        'dbPort'      => 5432,
        'dbName'  => 'modl')
    );

$db->setUser('edhelas@movim.eu');
$db->connect();


$db->check();

/*$pd = new PresenceDAO();
var_dump($pd->getAll());
var_dump($pd->clearPresence('test@movim.eu'));

$p = new Presence();
$p->session = 'test@movim.eu';
$p->jid = 'vanaryon@movim.eu';
$p->ressource = 'lol';
$p->value = '3';
$p->priority = '1';

$pd->set($p);

$p->value = '4';
$pd->set($p);

$p = new Presence();
$p->session = 'test@movim.eu';
$p->jid = 'vanaryon@movim.eu';
$p->ressource = 'lol2';
$p->value = '3';
$p->priority = '1';

$pd->set($p);
*/
//var_dump($pd->getJid('valerian@jappix.com'));

//$pd = new ContactDAO();
//var_dump($pd->getAll());

/*Privacy::set('lol',1);
Privacy::set('lol',2);
Privacy::set('lol',3);
Privacy::set('lol2',4);
Privacy::set('lol2',5);

//$pr->set('lol',2);
//$prd->set($pr);

$prd = new PrivacyDAO();
var_dump($prd->get('lol'));
var_dump($prd->get('lol2'));
*/
$pd = new ContactDAO();

$c = new Contact();
$c->jid = "lol";
$c->tuneartist = "The Beatles";
$pd->set($c);

var_dump($pd->getAll());
var_dump($pd->getRoster());
