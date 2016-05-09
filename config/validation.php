<?php

return [
    'tags'          => 'array|max:20',
    'tag'           => 'string|max:20|regex:/^[a-zA-ZåäöÅÄÖ]{1}[\w\#åäöÅÄÖ]+$/',
    'title'         => 'required|max:100',
    'description'   => 'max:5000',
];
