<?php
$roleLevel = [
    "siswa" => 1,
    "petugas" => 2,
    "admin" => 3,
    "superadmin" => 4
];

function roleConvert($role)
{
    global $roleLevel;

    if (is_int($role)) {
        return $role;
    } else {
        return $roleLevel[$role];
    };
};

function roleCheckMinimum($role, $level)
{
    if (roleConvert($role) >= roleConvert($level)) {
        return true;
    } else {
        return false;
    };
};

function roleCheckSingle($role, $level)
{
    if (roleConvert($role) == roleConvert($level)) {
        return true;
    } else {
        return false;
    };
};

function roleGuardMinimum($role, $level, $path)
{
    if (!roleCheckMinimum($role, $level)) {
        echo "<script>window.location='$path';</script>";
    };
};

function roleGuardSingle($role, $level, $path)
{
    if (!roleCheckSingle($role, $level)) {
        echo "<script>window.location='$path';</script>";
    };
};
