<?php
namespace app\admin\model;
use think\Model;
class Classes extends Model
{
        protected $table = 'classes';

        public function getClassInfo($where)
        {
                $classInfo = collection($this->where($where)->select())->toArray();
                foreach ($classInfo as $k => $v) {
                        $addressInfo[$k]['class'] = model('Classes')->where(['pid' => $v['province']])->value('class_name');
                        $addressInfo[$k]['student'] = model('Classes')->where(['pid' => $v['city']])->value('class_name');

                }
                return $classInfo;
        }
}