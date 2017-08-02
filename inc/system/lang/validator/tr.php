<?php
/**
 * :attribute => input name
 * :params => rule parameters ( eg: :params(0) = 10 of max_length(10) )
 */
return array(
    'required' => ':attribute alanı gerekli',
    'integer' => ':attribute alanı tam sayı olmalı',
    'float' => ':attribute alanı gerçek sayı olmalı',
    'numeric' => ':attribute alanı numerik bir değer olmalı',
    'email' => ':attribute alanı email formatında olmalı',
    'alpha' => ':attribute alanı sadece harflerden olmalı',
    'alpha_numeric' => ':attribute alanı sadece harf ve sayılardan olmalı',
    'ip' => ':attribute alanı IP formatına uymalı',
    'url' => ':attribute alanı geçerli bir url olmalı',
    'max_length' => ':attribute alanı en fazla :params(0) karakter uzunluğunda olabilir',
    'min_length' => ':attribute alanı en az :params(0) karakter uzunluğunda olabilir',
    'exact_length' => ':attribute alanı tam olarak :params(0) karakter uzunluğunda olabilir',
    'equals' => ':attribute alanı :params(0) değeri ile tam olarak aynı olmalı',
    'boolean' => ':attribute alanı mantıksal bir değer olmalı',
    'max_val' => ':attribute alanı en fazla :params(0) olabilir',
    'min_val' => ':attribute alanı en az :params(0) olabilir',
);