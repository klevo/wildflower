<?php 
class UserFixture extends CakeTestFixture {
    public $name = 'User';
    public $import = 'User';
    public $records = array(
        array('id' => 1, 
              'login' => 'klevo', 
              'password' => '9955d48db124ffd72e73e7400271e9d38e4b4358', // article123
              'email' => 'klevoooo@klevo.sk',
              'name' => 'Róbert Starší', 
              'cookie_token' => '38499d8c-689c-7bf4-45fb-50dbf5edda61', 
              'created' => '2007-08-14 11:28:22', 
              'updated' => '2008-05-13 19:45:38'),
        array('id' => 3, 
              'login' => 'admin', 
              'password' => '156e058b2b08882cf108b7711ae25d4221ae8e62',  // čýáíľššýíáč
              'email' => 'admin@admin.com',
              'name' => 'John Rambo', 
              'cookie_token' => '38499d8c-689c-7bf4-45fb-50dbf5edda61', 
              'created' => '2007-08-14 11:28:22', 
              'updated' => '2008-05-13 19:45:38')
    );
}
