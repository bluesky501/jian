<?php

namespace App\Admin\Controllers;

use App\Models\Autosell;
use App\Models\Custom;
use App\Models\Item;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Chart\Bar;
use Encore\Admin\Widgets\Chart\Doughnut;
use Encore\Admin\Widgets\Chart\Line;
use Encore\Admin\Widgets\Chart\Pie;
use Encore\Admin\Widgets\Chart\PolarArea;
use Encore\Admin\Widgets\Chart\Radar;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Dashboard');
            $content->description('Description...');

            $content->row(function ($row) {
                $orders = Autosell::count();
                $customs = Custom::count();
                $shouru = round(Autosell::sum('item_price_no')/10000);
                $stores = Item::count();
                $row->column(3, new InfoBox('成交量(单)', 'shopping-cart', 'green', '/admin/orders', $orders));
                $row->column(3, new InfoBox('客户量(个)', 'users', 'aqua', '/admin/users', $customs));
                $row->column(3, new InfoBox('整车收入(万元)', 'book', 'yellow', '/admin/articles', $shouru));
                $row->column(3, new InfoBox('整车库存(台)', 'file', 'red', '/admin/files', $stores));
            });

            $content->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $data['零售'] = Item::where('status', 1)->count();
                    $data['批发'] = Item::where('status', 2)->count();
                    $data['其他'] = Item::where('status', 3)->count();
                    $doughnut = new Doughnut([$data]);
                    $column->append((new Box('批零比', $doughnut))->removable()->collapsable()->style('info'));
                });

                $row->column(6, function (Column $column) {
                    $column->append((new Box('重点车型毛利率', new Line()))->removable()->collapsable()->style('danger'));
                });

            });

            $headers = ['序号', '公司', '库存（台）', '销量（台）', '整车收入（万元）', '整车毛利（万元）','整车票差毛利率%'];
            $rows = [
                [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica', '1997-08-13 13:59:21', 'open'],
                [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar', '1988-07-19 03:19:08', 'blocked'],
                [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC', '1978-06-19 11:12:57', 'blocked'],
                [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor', '1988-09-07 23:57:45', 'open'],
                [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.', 'Braun Ltd', '2013-10-16 10:00:01', 'open'],
            ];

            $content->row((new Box('主要店面数据', new Table($headers, $rows)))->style('info')->solid());
        });
    }
}
