<?php

interface UsersStorageInterface
{

    public function import();
    public function export($data);

}