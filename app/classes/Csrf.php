<?php

namespace app\classes;

class Csrf
{
    public static function csrf($request, $csrf)
    {
        $nameKey = $csrf->getTokenNameKey();
        $valueKey = $csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $name,
            'value' => $value,
        ];
    }
}
