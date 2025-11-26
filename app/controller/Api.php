<?php

namespace app\controller;

use app\Request;

class Api
{



    public function init(Request $request){
        $path = '{
            "homeInfo": {
                "title": "会员管理",
    "href": "/admin/users_list"
  },
  "logoInfo": {
                "title": "User",
    "image": "/images/logo.png",
    "href": ""
  },
  "menuInfo": [
    {
        "title": "",
      "icon": "fa fa-address-book",
      "href": "",
      "target": "_self",
      "child": [
        {
            "title": "账号管理",
          "href": "/admin/accounts_list",
          "icon": "fa fa-asterisk",
          "target": "_self"
        },
        {
            "title": "会员管理",
          "href": "/admin/users_list",
          "icon": "fa fa-asterisk",
          "target": "_self"
        }
      ]
    }
  ]
}';

        $path = json_decode($path,true);

        if($request->user_info['type'] == 'administrator' || $request->user_info['type'] == 'super'){
            return json($path);
        }

        unset($path['menuInfo'][0]['child'][0]);

        return json($path);
    }

}