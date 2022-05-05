<?php

namespace App\Admin\Controllers;

use App\Models\Prize;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PrizeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Prize';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Prize());

        $grid->column('id', __('Id'));
        $grid->column('payment_id', __('Payment id'));
        $grid->column('user_id', __('User id'));
        $grid->column('share_id', __('Share id'));
        $grid->column('cnt', __('Cnt'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Prize::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('payment_id', __('Payment id'));
        $show->field('user_id', __('User id'));
        $show->field('share_id', __('Share id'));
        $show->field('cnt', __('Cnt'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Prize());

        $form->number('payment_id', __('Payment id'));
        $form->number('user_id', __('User id'));
        $form->number('share_id', __('Share id'));
        $form->number('cnt', __('Cnt'));
        $form->text('status', __('Status'));

        return $form;
    }
}
