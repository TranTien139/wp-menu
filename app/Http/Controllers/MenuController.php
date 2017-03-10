<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use \App\models\Menu;
use \App\models\MenuItem;
use Input;
class MenuController extends Controller
{
    function home(){

        $menulist = Menu::pluck("name", "id");
        $menulist[0] = "Select menu";
        if (Input::has("action")) {
            return view('welcome')-> with("menulist", $menulist);
        } else {
            $menu = Menu::find(Input::get("menu"));
            $menus = MenuItem::where("menu", Input::get("menu")) -> orderBy("sort", "asc") -> get();
            return view('welcome')-> with("menus", $menus) -> with("indmenu", $menu) -> with("menulist", $menulist);

        }
    }

    public function createnewmenu() {
        $menu = new Menu();
        $menu ->name = Input::get("menuname");
        $menu -> save();
        return json_encode(array("resp" => $menu -> id));
    }

    public function deleteitemmenu() {
        $menuitem = MenuItem::find(Input::get("id"));
        $menuitem -> delete();
    }

    public function deletemenug() {
        $menus = new MenuItem();
        $getall = MenuItem::where("menu", Input::get("menu")) -> orderBy("sort", "asc") -> get();
        if (count($getall) == 0) {
            $menudelete = Menu::find(Input::get("id"));
            $menudelete -> delete();

            return json_encode(array("resp" => "you delete this item"));
        } else {
            return json_encode(array("resp" => "You have to delete all items first", "error" => 1));

        }
    }

    public function updateitem() {

        $menuitem = MenuItem::find(Input::get("id"));
        $menuitem -> label = Input::get("label");
        $menuitem -> link = Input::get("url");
        $menuitem -> class = Input::get("clases");
		$menuitem -> save();
	}

    public function addcustommenu() {

        $menuitem = new MenuItem();
        $menuitem -> label = Input::get("labelmenu");
        $menuitem -> link = Input::get("linkmenu");
        $menuitem -> menu = Input::get("idmenu");
        $menuitem -> save();

    }

    public function generatemenucontrol() {
        $menu = Menu::find(Input::get("idmenu"));
        $menu -> name = Input::get("menuname");
        $menu -> save();
        var_dump(Input::get("arraydata"));
        foreach (Input::get("arraydata") as $value) {

            $menuitem = MenuItem::find($value["id"]);
            $menuitem -> parent = $value["parent"];
            $menuitem -> sort = $value["sort"];
            $menuitem -> depth = $value["depth"];
            $menuitem -> save();
        }
    }

}
