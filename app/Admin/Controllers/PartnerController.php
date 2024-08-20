<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\UserProfile;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PartnerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Партнеры';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->model()->where(['user_type' => 'partner']);
        $grid->model()->join('user_profile', 'user_profile.user_id', '=', 'users.id');
        $grid->model()->selectRaw('user_profile.*, users.id');
        $grid->column('id', __('Id'));

        $grid->column('company', __('Компания'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Телефон'));
        $grid->column('percent', __('Процент, %'));

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('company', __('Компания'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Телефон'));
        $show->field('percent', __('Процент, %'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('profile.company', 'Компания');
        $form->text('profile.email', 'Email');
        $form->text('profile.phone', 'Телефон');
        $form->text('profile.percent', 'Процент, %');

        return $form;
    }
}
