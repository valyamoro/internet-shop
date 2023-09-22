<?php

function formatting($data) {
    foreach ($data as $q) {
        $formData = explode('|', $q);
        $formDatas[] = $formData;
    }
    return $formDatas;
}
