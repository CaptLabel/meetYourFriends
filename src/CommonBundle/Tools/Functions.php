<?php

namespace CommonBundle\Tools;

class Functions
{
    public function createPass($pass){
        $grain = 'b54sFmjJ52';
        $sel = 'a12Gfd51gzR';
        $sha1 = sha1($grain.$pass.$sel);
        return $sha1;
    }
    public function createKeySecure($email){
        $timestamp = 1234567890;
        $date_time = \Date('dmY', $timestamp);
        $key = $email . $date_time;
        $hash = hash('sha256', $key);
        return $hash;
    }
}