<?php

class User
{
    public string $id;
    public string $name;
    public string $email;
    public string $phone;
    public string $password;
    public string $nidCardNo;
    public string $profile_image;

    public string $role;   // USER | VENDOR | ADMIN
    public string $status; // ACTIVE | DEACTIVATED | BLOCKED | BLACKLISTED

    public ?string $shop_number = null;
    public ?string $business_card_no = null;

    public string $created_at;
    public ?string $deactivated_at = null;
}
