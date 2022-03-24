<?php


namespace App;


class GlobalRegex
{
    const NAME_PATTERN = '/^([A-Za-z\s]{2,30})$/';
    const PASSWORD_PATTERN = '/^(.{8,20})$/';
    const PHONE_PATTERN = '/^(0(5|6|7)[0-9]{8}$)|^(0[0-9]{8}$)/';
    const PASSCODE_PATTERN = '/^[A-Z0-9]{6}$/';
    const ORDER_ID_PATTERN = '#^[0-9]{2}(CO)[0-9]{4}$#';
    const facebook_url_pattern = '/https:\/\/www.facebook.com\/.*/';
    const instagram_url_pattern = '/https:\/\/www.instagram.com\/*/';
    const urlPattern = '/^(|https:\/\/www\.|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/';
}
