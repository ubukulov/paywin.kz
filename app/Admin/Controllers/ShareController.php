<?php

namespace App\Admin\Controllers;

use App\Models\Share;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShareController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Акции';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Share());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('Партнер'));
        $grid->column('type', __('Тип'));
        $grid->column('title', __('Название'));
        $grid->column('cnt', __('Кол-во'));
        $grid->column('promo', __('Promo'));
        $grid->column('size', __('Размер'));
        $grid->column('from_order', __('От'));
        $grid->column('to_order', __('До'));
        $grid->column('c_winning', __('Коэф.'));
        $grid->column('from_date', __('Начало'));
        $grid->column('to_date', __('Конец'));
        $grid->column('created_at', __('Дата'));
        $grid->column('updated_at', __('Обновлен'));

        $grid->user_id()->display(function($userId) {
            return User::find($userId)->profile->company;
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Share::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('type', __('Type'));
        $show->field('title', __('Title'));
        $show->field('cnt', __('Cnt'));
        $show->field('promo', __('Promo'));
        $show->field('size', __('Size'));
        $show->field('from_order', __('From order'));
        $show->field('to_order', __('To order'));
        $show->field('c_winning', __('C winning'));
        $show->field('from_date', __('From date'));
        $show->field('to_date', __('To date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Share());

        //$form->number('user_id', __('User id'));
        $form->select('user_id', 'Выберите партнера')->options(User::all()->pluck('phone', 'id'));
        //$form->text('type', __('Тип'));
        $form->select('type', 'Выберите тип')->options(['share' => 'Акция', 'discount' => 'Скидка', 'cashback' => 'Кешбек', 'promocode' => 'Промокод']);
        $form->text('title', __('Название'));
        $form->number('cnt', __('Кол-во'));
        //$form->text('promo', __('Promo'));
        $form->select('promo', 'Скидка/Деньги')->options(['none' => 'Нет', 'discount' => 'Скидка', 'money' => 'Деньги']);
        $form->number('size', __('Размер'));
        $form->number('from_order', __('От'));
        $form->number('to_order', __('До'));
        $form->number('c_winning', __('Коэф.'));
        $form->datetime('from_date', __('Начало'))->default(date('Y-m-d H:i:s'));
        $form->datetime('to_date', __('Конец'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
