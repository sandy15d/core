<?php

function get_studly_case($page_name){
    return \App\Models\PageBuilder::where('page_name',$page_name)->first()->studly_case;
}

function get_snake_case($page_name){
    return \App\Models\PageBuilder::where('page_name',$page_name)->first()->snake_case;
}
