<?php

namespace App\Admin\Controllers\Exam;

use App\Model\Exam\LegalModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class LegalController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LegalModel);

        $grid->id('Id');
        $grid->le_regnum('注册号');
        $grid->le_name('名称');
        $grid->le_person('代表人');
        $grid->le_address('住所');
        $grid->le_type('公司类型');
        $grid->le_date('注册时间')->display(function($le_date){
            return date('Y-m-d H:i:s',$le_date);
        });
        $grid->le_appid('AppId');
        $grid->le_key('Key');
        $grid->le_status('审核状态')->display(function($le_status){
            if($le_status == 1){
                return $le_status='<span style="color:green">审核通过</span>';
            }elseif($le_status == 2){
                return $le_status='<span style="color:red">审核中</span>';
            }
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
        $show = new Show(LegalModel::findOrFail($id));

        $show->id('Id');
        $show->le_name('Le name');
        $show->le_person('Le person');
        $show->le_address('Le address');
        $show->le_type('Le type');
        $show->le_date('Le date');
        $show->le_appid('Le appid');
        $show->le_key('Le key');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new LegalModel);

        $form->text('le_name', 'Le name');
        $form->text('le_person', 'Le person');
        $form->text('le_address', 'Le address');
        $form->text('le_type', 'Le type');
        $form->number('le_date', 'Le date');
        $form->text('le_appid', 'Le appid');
        $form->text('le_key', 'Le key');

        return $form;
    }
}
