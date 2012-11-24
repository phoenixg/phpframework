<?php

class DefaultActions extends nbAction {

    public function indexAction() {
        $this->setTemplate("index");
        $this->content = $this->request->getPost('content');
        $num = $this->request->getPost("num");
        if ($this->content) {
            $sql = explode(";", $this->content);
            foreach ($sql as $key => $value) {
                if ($num) {
                    for ($i = 1; $i < $num; $i++) {
                        $this->service->query($value);
                    }
                }
                $this->resultArr[$key][0] = $this->service->query($value);
                $this->resultArr[$key][1] = $value;
                $this->resultArr[$key][2] = $this->service->getAffectedRowNum();
                $this->resultArr[$key][3] = $num ? $num : 0;
            }
        }
    }

}