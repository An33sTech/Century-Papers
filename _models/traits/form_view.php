<?php



/* print Form tested examples, if any option you don't want just remove it..

 * */

/*

/////Form Create With array

//Examples



$form_fields = array();

//Make <form, call first or any where then make array index key is 'form',

//now mange more clear, just make format here... no thisFormat work here.



$form_fields['form']  = array(

    'name'      => "form",

    'type'      => 'form',

    'class'     => "form-horizontal",

    'id'        => "formId",

    'data'     => "data-id='formdata'",

    'action'   => '',

    'method'   => 'post',

    'format'   => '<div class="form-horizontal">{{form}}</div>'

);



//if you dont want to use <form and want to put all fields inside other div,

//best and clean way is // only format tag use

$form_fields['main'] = array(

    'format' => "<div class='form-horizontal'>{{form}}</div>"

);



//All Array key are optionals

//Text Field,email,number,url,password,hidden



$form_fields[] = array(

    'label' => "Text",

    'name'  => 'Text1',

    'placeholder' => "",

    'value' => "Default",

    'type'  => 'text',

    'class' => 'form-control',

);



$form_fields[] = array(

    'label' => "Text field",

    'name'  => 'Text1',

    'placeholder' => _uc($_e['Measurement Title']),

    'value' => "Default",

    'type'  => 'text',

    'class' => 'form-control',

    'id'  => 'textId',

    'required'  => 'true',

    'min-length'  => '30',

    'max-length'  => '50',



    'pattern'     => '[a-z]',

    'data' => 'data-id="10" data-name="formAuto"',

    'format' => '<div class="input-group">

                  <div class="input-group-addon">$</div>

                      {{form}}

                  <div class="input-group-addon">.00</div>

                </div>',

    'formatHead' => '<div>{{form}}</div>', //its need in radio to print format inside format its not effet main format,radio print multiple, so all parent format is set here..

    'thisFormat' => '<div></div>', // $format will not work with this key

    'group'  => 'groupName', // want to print some group array in one div? easy to manage and view,,, use group key with same group name...

    // then where you want to print use type='group', and name = 'groupName'

    //if group key exists, it will not print until call from type = group..

);



//Group Name || Group Array

//this array must be call after all same group array execute/declare

$form_fields[] = array(

    'type' => 'group',

    'name' => 'groupName',

    'label' => 'optional',

    'group' => 'groupInsideGroup', //you can also use group inside other group

    'thisFormat' => '<div class="panel-group" id="accordion">{{form}}</div><!--#accordion-->',

    'format' => 'it will use default format {{form}} same as other'

    //if no format, it will consider default all format

);







//inFormat , When 2 or more field want to print in other div, inFormat is unique name. you can alos use group with inFormat.

$form_fields[] = array(

    'name'      => "submit",

    'type'      => 'submit',

    'class'     => 'btn themeButton',

    'value'     => $_e['Submit'],

    'thisFormat'=> "",

    'inFormat'  => 'save'

);



//inFromat Submit Button

$form_fields[] = array(

    'name'      => "submit",

    'type'      => 'submit',

    'class'     => 'btn themeButton',

    'value'     => $_e['Save'],

    'thisFormat'=> "",

    'inFormat'  => 'submit'

);



$form_fields[] = array(

    'type'      => "none",

    'format'    => '{{save}} {{submit}}',

);

//In end all match inFormat name will be replace.

//inFormat End





//Text Field,email,number,url,password,hidden

$form_fields[] = array(

    'label' => "Text field2",

    'name'  => 'Text2',

    'value' => "Example 2 no css",

    'type'  => 'search',

);



//Number Field

$form_fields[] = array(

    'label' => "number",

    'name'  => 'num',

    'value' => "10",

    'type'  => 'tel',

    'class' => 'form-control',

    'required' => 'true',

    'min'   => '10',

    'max'   => '50',

    'format' => '<div class="col-sm-3">{{form}}</div>'

);



//date Field

$form_fields[] = array(

    'label' => "Date",

    'name'  => 'date',

    'type'  => 'date',

    'class' => 'form-control',

    'required' => 'true',

    'format' => '<div class="col-sm-3">{{form}}</div>'

);



//TextArea

$form_fields[] = array(

    'label' => "test",

    'name'  => 'measurement_headinga',

    'value' => "val <textarea> ''".'" asad" ',

    'type'  => 'textarea',

    'class' => 'form-control',

    'max-length' => '500',

    'min-length' => '100',

);



//Radio Buttons with array control

$form_fields[] = array(

    'label' => "radio",

    'option' => array("male","female","ali","hassan"),

    'name'  => 'radio',

    'value' => array("male","female","ali","hassan"),

    'type'  => 'radio',

    'class' => '',

    'data' => 'data-if="as"',

    'selected' => array('female'),

    'required'  => 'true',

    'format' => '<label class="radio-inline">{{form}} {{option}}</label>'

);



//Radio Buttons with comma seprate values

$form_fields[] = array(

    'label' => "radio Comma seprate",

    'option' => "male1,female1,ali1,hassan1",

    'name'  => 'radio2',

    'value' => "male,female,ali,hassan",

    'type'  => 'radio',

    'class' => '',

    'data' => 'data-if="as"',

    'selected' => 'female',

    'format' => '<label class="radio-inline">{{form}} {{option}}</label>'

    //'formatHead' => '<div>{{form}}</div>' its need in radio to print format inside format its not effet main format

);



//CheckBox with array seprate values

$form_fields[] = array(

    'label' => "chekbox inner Array",

    'option' => array("male1","female1","ali1","hassan1"),

    'name'  => 'check',

    'value' => array("male","female","ali","hassan"),

    'type'  => 'check',

    'class' => '',

    'selected' => array('female','male'),

    'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>'

);



//CheckBox with comma seprate values

$form_fields[] = array(

    'label' => "chekbox with comma",

    'option' => "male1,female1,ali1,hassan1",

    'name'  => 'check',

    'value' => "male,female,ali,hassan",

    'type'  => 'check',

    'class' => '',

    'selected' => 'female,male',

    'format' => '<label class="checkbox-inline">{{form}} {{option}}</label>'

);



//Select Box

$form_fields[] = array(

    'label' => "select",



    'option' => array("male1","female1","ali1","hassan1"),

    'optionClass' => "optClass",

    'optionData' => "data-field='1' data-f2='2'",

    'value' => array("male","female","ali","hassan"),



    'name'  => 'select',

    'type'  => 'select',

    'data'  => 'data-select="selectdata"',

    'class' => 'form-control',

    'id'    => 'test',

    'selected' => 'female,male',

    'multi' => 'true',

    'format' => '<div class="col-sm-8">{{form}}</div>'

);



//Select example 2

$form_fields[] = array(

    'label' => "Select example 2",



    'option' => array("---","male1","female1","ali1","hassan1"),

    'value' => array("","male","female","ali","hassan"),



    'name'  => 'select',

    'type'  => 'select',

    'class' => 'form-control',

);



//Select example 3

$form_fields[] = array(

    'label' => "Select example 3",



    'option' => array("---","male1","female1","ali1","hassan1"),

    'value' => array("","male","female","ali","hassan"),



    'name'  => 'select',

    'type'  => 'select',

    "check" => 'female'

);



//select Example 4 , single Array with KEY, Key is value and value is options

$form_fields[] = array(

    'label' => 'Job Status',

    'name'  => 'rank',

    'array' => array("PK"=>"Pakistan","IN"=>"India","USA"=>"America"),

    'type'  => 'select',

    'class' => 'form-control',

);



//real Example

$sql    =   "SELECT * FROM `p_custom` WHERE publish = '1'";

$data = $dbF->getRows($sql);



$customTypeArray = array(); // for initial value

$customTypeArray['value']   = "0";

$customTypeArray['option']  = "----------";

$customTypeArray = $functions->getSelectValueAndOptions($data,'id','custom_type',false,$customTypeArray);



//Custom Size type select

$label = "select value";

$form_fields[] = array(

    'label' => _uc($_e['Custom Size Type']),

    'name'  => $product->prefix_setting.'[customSize]',

    'select' => "$label",

    'value'  => $customTypeArray['value'],

    'option' => $customTypeArray['option'],

    'type'  => 'select',

    'class' => 'form-control',

);



//real select example end



$form_fields[] = array(

    'name'  => 'test',

    'value' => "asad",

    'type'  => 'hidden',

);



//new Admin File Select field

$form_fields[] = array(

    'label'  => 'New Admin file',

    'name' => "new",

    'type'  => 'url',

    'id'    => 'favicon',

    'class' => 'form-control',

    'format' => '<div class="input-group">

                    {{form}}

                    <div class="input-group-addon linkList pointer"

                        onclick="openKCFinderImage($(\'#favicon\'))"><i class="glyphicon glyphicon-picture"></i></div>

                </div>'

);



//File Image Upload Field

$form_fields[] = array(

    'label' => 'image',

    'name'  => 'image',

    'type'  => 'image',

    'required'  => 'true',

    'filter'  => 'image',

    'format'  => '<div class="col-sm-10 col-md-9">{{form}} {{image}}</div>',

    'multi' => 'true',

    'class' => 'img',

    'image'  => 'image/image.png',

    'imageClass'  => 'abClass',

    'imageData'  => 'data-id="data"',

);



//File Upload Field

$form_fields[] = array(

    'label' => 'file',

    'name'  => 'file',

    'type'  => 'file',

    'format'  => '<div class="col-sm-10 col-md-9">{{form}}</div>',

);



//Submit Button

$form_fields[]  = array(

    "label" => "submit",

    "name"  => 'btn',

    'class' => 'btn btn-default',

    'type'  => 'submit'

);



//Submit <button

$form_fields[] = array(

    'name'      => "submit",

    'type'      => 'button',

    'class'     => 'btn themeButton',

    'value'     => 'Save',

    'option'     => $_e['Save'], // if option not set, value show.

    'thisFormat'=> "", // required to set this, for stop format on this.... if want only field result.

    'inFormat'  => 'submit',

    'submit'    => 'true' // use this for change type button to submit.

);



//Button, with own format , $format will not works

$form_fields[]  = array(

    "name"  => 'btn',

    'class' => 'btn btn-default',

    'type'  => 'button',

    'value' => 'btn',

    'thisFormat' => '<div class="form-group">

                       {{form}}

                     </div>'

);



// Bootstrap Switch Button Publish Or draft

$valFormTemp   =   '1';

$form_fields[]  = array(

    "label" => $_e['Publish'],

    'type'  => 'checkbox',

    'value' => "$valFormTemp",

    'select' => "$valFormTemp",

    'format' => '<div class="make-switch" data-off="danger" data-on="success" data-on-label="'. _uc($_e['Publish']) .'" data-off-label="'. _uc($_e['Draft']) .'">

                    {{form}}

                  <input type="hidden" name="insert[publish]" class="checkboxHidden" value="'.$valFormTemp.'" />

                 </div>'

);



//want to send some thing between form

$form_fields[] = array(

    'format' => "<hr><hr><hr>",

    //OR 'thisFormat' => "<hr><hr><hr>"

);



$format = '<div class="form-group">

                        <label class="col-sm-2 col-md-3  control-label">{{label}}</label>

                        <div class="col-sm-10  col-md-9">

                            {{form}}

                        </div>

                    </div>';

//$format = false;

$this->functions->print_form($form_fields,$format);

*/





// image,file, new admin panel field and image



trait form_view

{



    private $groups;

    private $inFormat;

    public function print_form($args,$format=false,$echo = true){

        $this->groups   = array();

        $this->inFormat = array();

        if($format === false){

            $format = "{{form}}";

        }



        //form layout + form fields

        /*

         * format= how you want to view

         * args = array contains

            'label'     => "Label Name",

            'name'      => 'name of Field',

            'placeholder' => "Place Holder",

            'value'     => "field value",

            'type'      => 'text',

                | email | number | (password,pass) | radio | (checkbox,check) | textarea | date | (url,link) |

                | (select,selectbox,option,options,drop,dropdown,combobox) | (hidden,hide)

                | (search,find) | (tel,phone)

                | image | file | button | submit

            'class'     => 'Field Class form-control',

            'id'        => 'Filed Id',

            'required'      => 'true', true,false => default false

            'min-length'  => '30',

            'max-length'  => '50',

            'min'       => '10' special use for number field

            'max'       => '10' special use for number field

            'pattern'   => '[a-z]',

            'data'      => 'data-id="10" data-name="formAuto"', => use for your own properties

            'format'    => '<div class="col-sm-6">{{form}}</div>' || optional, for specific format inside parent format



            'option'    => 'option Value' use for radio|checkbox|select value for user

            'select'    => 'default select value' // special use for radio,checkbox select box

                | you can use this feature with different keys

                | select,selected,check,checked



            'thisFormat' => "{{form}} {{label}}"; when this key exists $format parameter will not work.. thisFormat replace $format = thisFormat

            'formatHead' => '<div>{{form}}</div>' its need in radio to print format inside format its not effet main format, radio print multiple, so all parent format is set here..

            'inFormat'   =>  When 2 or more field want to print in other div, inFormat is unique name. you can alos use group with inFormat.

            'submit'      => 'true', true,false => default false,  // use this in type button, for change type button to submit.



        // For select and file tag

            'multiple'      => 'true' true|false for select multiple options

                |   key can use  multi|multiple



        // Only For Select Area

            'optionClass'   => 'class name' for <option class>

            'optionData'   => 'data properties' for own properties <option data-id="" dta-name=""



        // Only For Files Or images Upload

            'filter'        => 'image' image|video|audio this is for filter when pop window show to select file

            'image'         => 'old Image link'

            'imageClass'    => 'abClass', class on old <img tag

            'imageData'     => 'data-id="data"', data on old image tag

            'format'        => '<div class="col-sm-10 col-md-9">{{image}} {{form}}</div>', {{image}} for Old Image Placement





        //type = 'none' this is for you don't want form, just want to place some code on that places



         */

        //use data for your properties, like data-val,data-id

        //use 'data' => 'data-id="10" data-pro="text"'



        $form = '';

        foreach($args as $val){

            @$type       = $this->modify_type($val['type']); // check and make if perfect type, to reduce errors

            $form       .= $this->form_sendInRightFunction($format,$val,$type); //check type and send for make form to right function

        }



        if(isset($args[0]['type']) && $args[0]['type'] == 'form'){

            //print <form tag || if first array type is form

            $form = $this->formActionPrint($form,$args[0]);

        }elseif(isset($args['form']['type']) && $args['form']['type'] == 'form'){

            //print <form tag || key form in array

            $form = $this->formActionPrint($form,$args['form']);

        }



        //var_dump($this->groups);



        if(isset($args['main'])){

            //print main format,, if needed, out side or <form tag

            $mainFormat = $args['main']['format'];

            if(stristr($mainFormat,"{{form}}")) {

                $form = str_replace("{{form}}", $form, $mainFormat);

            }

        }



        $form = preg_replace("/{{form}}|{{label}}|{{option}}|{{image}}/i","",$form); // if any else {{form}} left, make it blank



        $form = $this->print_inFormat_fields($form); // print or include inFormat array into its place.

        if($echo) {

            echo $form;

        }else{

            return $form;

        }

    }



    private function formActionPrint($form,$args){

        //print <form action="" method="" enctype=""> tag

        $property   = $this->form_property($args);



        $element        = '';

        $elementStart   = '<form ';

        $elementEnd     = '</form>';



        $action         = empty($args['action']) ? "" : $args['action'];

        $method         = empty($args['method']) ? "get" : $args['method'];





        foreach($property as $key=> $val){

            if($val != '' && $key != 'label' && $key != 'type'){

                if($key=='data'){

                    $element .= " ".$val." ";

                }else {

                    $element .= $key . ' = "' . htmlentities($val) . '" ';

                }

            }

        }

        $element .= ' action = "' . htmlentities($action) . '" ';

        $element .= ' method = "' . htmlentities($method) . '" ';



        $temp  = $elementStart.$element." enctype='multipart/form-data'>".$form;

        if(isset($args['format']) && $args['format']!=''){

            $format = $args['format'];

            $format = str_replace("{{form}}",$temp,$format);

        }else{

            $format = $temp;

        }



        return $format.$elementEnd;

    }



    private function form_property($args){

        //function to return fields name, decrease chance of error

        //if you want to use data for your properties, like data-val,data-id

        //use 'data' => 'data-id="10" data-pro="text"'

        $temp = array();

        @$temp['label']     = stripslashes($args['label']);

        @$temp['type']       = $this->modify_type($args['type']);

        @$temp['name']       = $args['name'];

        @$temp['value']     = (($args['value']));

        @$temp['id']        = $args['id'];

        @$temp['class']     = $args['class'];



        @$temp['placeholder'] = '';

        if(isset($args['placeholder']))

            @$temp['placeholder'] = stripslashes($args['placeholder']);

        else if(isset($args['place']))

            @$temp['placeholder'] = stripslashes($args['place']);



        if(empty($temp['placeholder'])){

            $temp['placeholder'] = $temp['label'];

        }



        @$temp['max-length']= $args['max-length'];

        @$temp['min-length']= $args['min-length'];

        @$temp['min']       = $args['min'];

        @$temp['max']       = $args['max'];

        @$temp['pattern']   = $args['pattern'];

        @$temp['data']      = stripslashes($args['data']);



        $temp['required']   = false;

        @$required          =   $args['required'];

        if($required=='true' || $required == 'on' || $required == '1'){

            $temp['required']  = true;

        }



        $temp['readonly']   = false;

        @$readonly          =   $args['readonly'];

        if($readonly=='true' || $readonly == 'on' || $readonly == '1'){

            $temp['readonly']  = true;

        }





        return $temp;

    }





    private function form_sendInRightFunction($format,$args,$type){

        //check field type and send to make field layout

        $temp = '';

        switch($type){

            case "text":

            case "email":

            case "number":

            case "url":

            case "password":

            case "date":

            case "search":

            case "tel":

            case "submit":

            case "reset":

                $temp = $this->print_form_text($format,$args);

                break;

            case "button":

                $temp = $this->print_form_button($format,$args);

                break;

            case "textarea":

                $temp = $this->print_form_textArea($format,$args);

                break;

            case "radio":

            case "checkbox":

                $temp = $this->print_form_radio($format,$args);

                break;

            case "select":

                $temp = $this->print_form_select($format,$args);

                break;

            case "hidden":

                $temp = $this->print_form_hidden($format,$args);

                break;

            case "file":

                $temp = $this->print_form_file($format,$args);

                break;

            case "group":

                $temp = $this->print_form_group($format,$args);

                break;

            case "none":

                $temp = $this->print_form_none($format,$args);

                break;





        }



        return $temp;

    }



    public function getSelectValueAndOptions($data,$valueName,$optionName,$blank=false,$before=array()){

        //take data array from table and change it into array of for select tag

        if(!empty($blank)) {

            $customTypeArray['value'][] = "";

            $customTypeArray['option'][] = "----------";

        }

        //before

        /*

         *  $customTypeArray['value']   = "0";

            $customTypeArray['option']  = "----------";

         */

        if(!empty($before)) {

            $customTypeArray['value'][] = $before['value'];

            $customTypeArray['option'][] = $before['option'];

        }

        foreach($data as $val){

            $customTypeArray['value'][]   = $val[$valueName];

            $customTypeArray['option'][] = $val[$optionName];

        }





        return $customTypeArray;

    }



    public function print_form_text($format,$args){

        //return input text with layout

        $property   = $this->form_property($args);

        $field      = $this->textFieldPrint($property,$args);

        $format     = $this->finalFormat($format,$field,$property,$args);

        return $format;

    }

    public function print_form_button($format,$args){

        //return input text with layout

        $property   = $this->form_property($args);

        $field      = $this->buttonFieldPrint($property,$args);

        $format     = $this->finalFormat($format,$field,$property,$args);

        return $format;

    }

    public function print_form_none($format,$args){

        //return input text with layout

        $property   = $this->form_property($args);

        @$field     = $args['format'];

        $format     = $this->finalFormat($format,$field,$property,$args);

        return $format;

    }



    public function print_form_file($format,$args){

        //return input text with layout

        $property = $this->form_property($args);

        $field = $this->fileFieldPrint($property,$args);

        $format = $this->finalFormat($format,$field,$property,$args);

        return $format;

    }



    public function print_form_hidden($format,$args){

        //return input hidden no layout

        $property = $this->form_property($args);

        $field = $this->textFieldPrint($property,$args);

        return $field;

    }



    public function print_form_textArea($format,$args){

        //return textarea with layout

        $property = $this->form_property($args);

        $field = $this->textAreaPrint($property,$args);

        $format = $this->finalFormat($format,$field,$property,$args);

        return $format;

    }



    public function print_form_radio($format,$args){

        //return textarea with layout

        $property = $this->form_property($args);

        $field = $this->radioPrint($property,$args);

        $format = $this->finalFormat($format,$field,$property,$args);

        return $format;

    }



    public function print_form_select($format,$args){

        //return selectBox with layout

        $property = $this->form_property($args);

        $field = $this->selectPrint($property,$args);

        $format = $this->finalFormat($format,$field,$property,$args);

        return $format;

    }





    private function finalFormat($format,$form,$property=array(),$args=array()){

        //check all format array keys, and replace with generated result. work on every single form field

        if(isset($args['formatHead'])){

            $formatHead = $args['formatHead'];

            $form = str_replace("{{form}}", $form, $formatHead);

        }



        if(isset($args['thisFormat'])){

            $format = $args['thisFormat'];

        }

        if ($format !== null) {
    $format = trim($format);
}


        if(!empty($format)) {

            $format = str_replace("{{label}}", @$property['label'], $format);

            @$format = str_replace("{{form}}", $form, $format);

        }else{

            $format = $form;

        }



        //inFormat, unique Field.

        if(isset($args['inFormat'])){

            $temp =$args['inFormat'];

            $this->inFormat[$temp] = $format;

        }





        //Group Manage, if group key , then it will not print until call

        if(isset($args['group'])){

            $temp =$args['group'];

            $this->groups[$temp][]= $format;

            return "";

        }else if(isset($args['inFormat'])) {

            return "";

        }else{

            return $format;

        }

    }



    private function print_inFormat_fields($form){

        // print or include inFormat array into its place in all arrays.

        if(!empty($this->inFormat)){

            foreach($this->inFormat as $key=>$val){

                $form = str_replace("{{{$key}}}",$val,$form);

            }

        }

        return $form;

    }



    private function print_form_group($format,$args){

        //gourp of fields print and manage here.

        if(isset($args['name'])){

            $groupPrint = $args['name'];

            if(isset($this->groups[$groupPrint])){

                $singleGroupArray = implode("",$this->groups[$groupPrint]);

                if(isset($args['thisFormat'])){

                    $format  = $args['thisFormat'];

                }else if(isset($args['format']) && $args['format']!=''){

                    $singleGroupArray = str_replace("{{form}}",$singleGroupArray,$args['format']);

                }



                $format = str_replace("{{form}}",$singleGroupArray,$format);

                if(isset($args['label'])){

                    $format = str_replace("{{label}}",$args['label'],$format);

                }





                //Placing Groups, if type group has also a group part

                if(isset($args['group'])){

                    $temp =$args['group'];

                    $this->groups[$temp][]= $format;

                    return "";

                }else{

                    //unset($this->groups[$groupPrint]);

                    return $format;

                }

            }

        }

        return "";

    }



    private function selectValue($args){

        //use in select, radio or checkbox

        if(isset($args['select'])){

            @$selected = $args['select'];

        }else if(isset($args['selected'])){

            @$selected = $args['selected'];

        }else if(isset($args['check'])){

            @$selected = $args['check'];

        }else if(isset($args['checked'])){

            @$selected = $args['checked'];

        }else{

            $selected = "false";

        }



        if(!is_array($selected)){

            $selected = explode(',',$selected);

        }

        return $selected;

    }



    private function selectPrint($property,$args=array()){

        //make select tag

        $temp   = '';

        if(isset($args['array']) && is_array($args['array'])) {

            @$array = $args['array'];

            $values = null;

            $options = null;

            foreach($array as $key=>$val){

                $values[]   = $key;

                $options[]  = $val;

            }

            $args['value']  = $values;

        }else {

            if (is_array($property['value'])) {

                @$values = $property['value'];

            } else {

                @$values = $property['value'];

                $values = trim($values, ",");

                @$values = explode(",", $values);

            }

            if (isset($args['option']) && is_array($args['option'])) {

                @$options = $args['option'];

            } else {

                @$options = $args['option'];

                $options = trim($options, ",");

                @$options = explode(",", $options);

            }

        }



        $selected = $this->selectValue($args);

        /* if(is_array($selected)){

             $selected = implode(',',$selected);

         }

         $selected = trim($selected,",");

         $selected = '["'.str_replace(",",'","',$selected).'"]';*/



        $name   = $property['name'];

        $id     = $property['id'];

        $id     = !empty($id) ? "id='$id'" : "";

        $class  = $property['class'];

        $required  = ($property['required']) ? "required  = 'true'" : "";

        $data  = $property['data'];



        if( (isset($args['multi']) && $args['multi'] !='false') ||

            (isset($args['multiple']) && $args['multiple'] !='false')) {

            $multiple = "multiple";

        }else{

            $multiple  = '';

        }



        $script = '';

        /*$script = '<script>

                $(document).ready(function(){

                    //$("select[name=\''.$name.'\'][value=\''.$selected.'\']").attr("selected", true);

                    $("select[name=\''.$name.'\']").val('.$selected.').change();

                });

                </script>';*/



        $selectStart = "<select name='$name' class='$class' $id $required $data $multiple>";

        $selectEnd = "</select>";



        foreach($values as $keyOption=>$radioVal) {

            $element = '';

            $elementStart = '<option ';

            $elementEnd = '</option>';

            foreach ($args as $key => $val){

                if ($val != ''){

                    if($key == 'optionClass' || $key=='optionData' || $key=='value'){

                        if($key == 'optionClass'){

                            $key = 'class';

                        }

                        $check = '';

                        if($key == 'value'){

                            $val = $radioVal;

                            if(in_array($val,$selected) || $val == $selected){

                                $check = "selected ";

                            }

                        }

                        if($key == 'optionData'){

                            $element .= " ".$val." ";

                        }else {

                            $element .= $key . ' = "' . htmlentities($val) . '" ' . $check;

                        }

                    }

                }

            }// single <option attributes check end.



            @$option = $options[$keyOption]; //get value <option>Value</option> from options variable

            $element = $element.">$option";



            $tempThis = $elementStart.$element.$elementEnd; //finaly single option complete



            $temp .= $tempThis;

        }



        $selectBox = $selectStart . $temp . $selectEnd.$script;



        if(isset($args['format']) && $args['format']!=''){

            $format = $args['format'];

            $format = str_replace("{{form}}",$selectBox,$format);

        }else{

            $format = $selectBox;

        }





        return $format;

    }



    private function radioPrint($property,$args=array()){

        //make radio buttons form

        $temp = '';



        if(is_array($property['value'])){

            @$values = $property['value'];

        }else {

            @$values = explode(",", $property['value']);

        }

        if(isset($args['option']) && is_array($args['option'])) {

            @$options = $args['option'];

        }else {

            @$options = explode(",", $args['option']);

        }



        $selected = $this->selectValue($args);



        $name = $property['name'];

        /* $temp .= '<script>

                 $(document).ready(function(){

                     $("input[name=\''.$name.'\'][value=\''.$selected.'\']").attr("checked", true);

                 });



                 </script>';*/



        foreach($values as $keyOption=>$radioVal) {

            $element = '';

            $elementStart = '<input ';

            $elementEnd = '/>';

            foreach ($property as $key => $val){

                if ($val != '' && $key != 'label'){

                    $check = '';

                    if($key=='value'){

                        $val = $radioVal;

                        if(in_array($val,$selected) || $val == $selected){

                            $check = "checked ";

                        }

                    }

                    if($key=='data'){

                        $element .= " ".$val." ";

                    }else {

                        $element .= $key . ' = "' . htmlentities($val) . '" '.$check;

                    }



                }

            }



            $tempThis = $elementStart.$element.$elementEnd;



            if(isset($args['format']) && $args['format']!=''){

                $format = $args['format'];

                @$option = $options[$keyOption];

                $format = str_replace("{{option}}",$option,$format);

                $format = str_replace("{{form}}",$tempThis,$format);

            }else{

                $format = $tempThis;

            }



            $temp .= $format;

        }





        return $temp;

    }



    private function textAreaPrint($property,$args=array()){

        //only textArea

        $element = '';

        $elementStart = '<textarea ';

        $elementEnd = '</textarea>';



        foreach($property as $key=> $val){

            if($val != '' && $key != 'value' && $key != 'label'){

                if($key=='data'){

                    $element .= " ".$val." ";

                }else {

                    $element .= $key . ' = "' . htmlentities($val) . '" ';

                }

            }

        }

        if (isset($property['value'])) {
    $element = $element . ' >' . htmlentities($property['value']);
} else {
    $element = $element . ' >'; // Add a fallback if $property['value'] is null
}




        $temp  = $elementStart.$element.$elementEnd;

        if(isset($args['format']) && $args['format']!=''){

            $format = $args['format'];

            $format = str_replace("{{form}}",$temp,$format);

        }else{

            $format = $temp;

        }



        return $format;

    }



    private function fileFieldPrint($property,$args=array()){

        //only text field or hidden field

        $element = '';

        $elementStart = '<input ';

        $elementEnd = '/>';

        @$filterType = $args['filter'];

        $filter = '';

        if($filterType == 'image'){

            $filter = ' accept="image/*" ';

        }elseif($filterType == 'video'){

            $filter = ' accept="video/*" ';

        }elseif($filterType == 'audio'){

            $filter = ' accept="audio/*" ';

        }elseif($filterType == 'pdf'){

            $filter = ' accept="application/pdf" ';

        }



        $multiple  = '';

        if( (isset($args['multi']) && $args['multi'] !='false') ||

            (isset($args['multiple']) && $args['multiple'] !='false')

        ){

            $multiple = "multiple";

        }



        foreach($property as $key=> $val){

            if($val != '' && $key != 'label'){

                if($key=='data'){

                    $element .= " ".$val." ";

                }else{

                    $element .= $key . ' = "' . htmlentities($val) . '" ';

                }

            }

        }

        $element = $element." $filter $multiple";



        $temp  = $elementStart.$element.$elementEnd;



        $oldImage = ''; // $oldFile | $oldImage if file then link show, if image then image show

        if(!empty($args['image']) || !empty($args['file'])){

            $oldImage = isset($args['image']) ? $args['image'] : @$args['file'] ;

            $class = '';

            if(!empty($args['imageClass']) || !empty($args['fileClass'])){

                $imgClass = isset($args['imageClass']) ? $args['imageClass'] : @$args['fileClass'] ;

                $class = 'class= "' . htmlentities($imgClass) . '"';

            }

            $imgData = '';

            if(!empty($args['imageData']) || !empty($args['fileData'])){

                $imgData = isset($args['imageData']) ? $args['imageData'] : @$args['fileData'] ;

                $imgData = " ".$imgData." ";

            }



            //set last image

            if(!empty($property['name'])){

                $temp1 = str_replace(WEB_URL."/images/","",$oldImage);

                $temp1 = str_replace(WEB_URL,"",$temp1);

                $oldImageHidden = "<input type='hidden' name='$property[name]_old' value='$temp1'/>";

            }else{

                $oldImageHidden = '';

            }



            if(!preg_match('@http://.*|https://.*@',$oldImage)){

                $oldImage = WEB_URL."/images/".$oldImage;

            }



            if(!empty($args['image']))

                $oldImage = "$oldImageHidden<img style='max-width:100%;' src='$oldImage' $class $imgData />";

            else

                $oldImage = "$oldImageHidden<a href='$oldImage' target='_blank' $class $imgData >".$this->get_file_name_from_link($oldImage)."</a>";

        }



        if(isset($args['format']) && $args['format']!=''){

            $format = $args['format'];

            $format = str_replace("{{form}}",$temp,$format);

            $format = str_replace("{{image}}",$oldImage,$format);

            $format = str_replace("{{file}}",$oldImage,$format);

        }else{

            $format = $temp;

        }

        return $format;

    }



private function textFieldPrint($property,$args=array()){
global $functions;
//only text field or hidden field

$element = '';

$elementStart = '<input ';

$elementEnd = '/>';

$lang = $functions->IbmsLanguages();

// var_dump($args['uploadtype']);
// if (@$args['uploadtype'] == 'b2' || @$args['uploadtype'] == 'b1') {

for ($i = 0; $i < sizeof($lang); $i++) {

// if ($args['uploadtype'] == 'image2') {
// 
// $imgDiv ='    <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('b2".$lang[$i]."')".'"><i class="glyphicon glyphicon-picture"></i></div>';

if (@$args['uploadtype'] == 'b2'.$lang[$i])
{
$elementEnd .= ' <div class="col-sm-10  col-md-11 ">

<img src="'.$args['value'].'" class="b2'.$lang[$i].' kcFinderImage"/>

</div><div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('b2".$lang[$i]."')".'"><i class="glyphicon glyphicon-picture"></i></div>';

}


// if (@$args['uploadtype'] == 'l2'.$lang[$i])
// {
// $elementEnd .= '<div class="col-sm-10  col-md-11 "><div class="input-group-addon linkList'.$lang[$i].' pointer"><i class="glyphicon glyphicon-search"></i>
// </div></div>';

// }




# code...
// }



// if ($args['uploadtype'] == 'file') {

// $fileDiv ='  <div class="input-group-addon pointer " onclick="'."openKCFinderFile($('classFile".$lang[$i]."'))".'"><i class="glyphicon glyphicon-file"></i></div>';
// $elementEnd = '/>>>>>'.$fileDiv;

// }



// if ($args['uploadtype'] == 'image') {

// $imgDiv ='    <div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('b1".$lang[$i]."')".'"><i class="glyphicon glyphicon-picture"></i></div>';


if (@$args['uploadtype'] == 'b1'.$lang[$i]){
$elementEnd .= ' <div class="col-sm-10  col-md-11 ">

<img src="'.$args['value'].'" class="b1'.$lang[$i].' kcFinderImage"/>

</div><div class="input-group-addon pointer " onclick="'."openKCFinderImageWithImg('b1".$lang[$i]."')".'"><i class="glyphicon glyphicon-picture"></i></div>';
}



// if (@$args['uploadtype'] == 'l1'.$lang[$i]){
// $elementEnd .= '<div class="col-sm-10  col-md-11 "><div class="input-group-addon linkList'.$lang[$i].' pointer"><i class="glyphicon glyphicon-search"></i>
// </div></div>';
// }








# code...
// }

// }
}

// }else{


//         $elementEnd = '/>';




// }
foreach( $property as $key=> $val ){

if($val!='' && $key != 'label' || ( ($val===0 || $val==='0')) && $key=='value'){

if($key=='data'){

$element .= " ".$val." ";

}else {

$element .= $key . ' = "' . htmlentities($val) . '" ';

}

}

}



        $temp  = $elementStart.$element.$elementEnd;

        if(isset($args['format']) && $args['format']!=''){

            $format = $args['format'];

            $format = str_replace("{{form}}",$temp,$format);

        }else{

            $format = $temp;

        }

        return $format;

    }



    private function buttonFieldPrint($property,$args=array()){

        //only text field or hidden field

        $element = '';

        $elementStart = '<button ';

        $elementEnd = '</button>';



        if(isset($property['value'])){

            @$options = $property['value'];

        }

        if(isset($args['option']) && !empty($args['option']) || empty($options)) {

            @$options = $args['option'];

        }



        if(isset($args['submit']) && $args['submit']=='true'){

            @$property['type'] = "submit";

        }





        foreach($property as $key=> $val){

            if($val != '' && $key != 'label' || ( ($val===0 || $val==='0')) && $key=='value'){

                if($key=='data'){

                    $element .= " ".$val." ";

                }else {

                    $element .= $key . ' = "' . htmlentities($val) . '" ';

                }

            }

        }



        $temp  = $elementStart.$element.">".$options.$elementEnd;

        if(isset($args['format']) && $args['format']!=''){

            $format = $args['format'];

            $format = str_replace("{{form}}",$temp,$format);

        }else{

            $format = $temp;

        }

        return $format;

    }



    private function modify_type($type){

        // make fields name correct

        $type = strtolower($type);

        switch($type){

            case "text":

                return "text";



            case "search":

            case "find":

                return "search";



            case "tel":

            case "phone":

            case "cell":

                return "tel";



            case "radio":

                return "radio";



            case "email":

                return "email";



            case "button":

            case "btn":

                return "button";



            case "submit":

                return "submit";

            case "reset":

                return "reset";



            case "textarea":

                return "textarea";



            case "number":

                return "number";



            case "group":

                return "group";



            case "checkbox":

            case "check":

                return "checkbox";



            case "date":

                return "date";



            case "url":

            case "link":

                return "url";



            case "password":

            case "pass":

                return "password";

            case "hidden":

            case "hide":

                return "hidden";



            case "select":

            case "selectbox":

            case "option":

            case "options":

            case "drop":

            case "dropdown":

            case "combobox":

                return "select";



            case "image":

            case "file":

            case "picture":

                return "file";

            case "form":

            case "main":

                return "";

            case "none":

            case "false":

            case false:

            case "0":

                return "none";

            default :

                return  "text";

                break;

        }

    }



    /**

     * @param $tableName

     * @param $array

     */

    /** array contain key as field Name, Value for insert */

    public function formInsert($tableName,$data,$has_htmlCharacter=false){

        $fields = '';

        $values = '';

        $formArray = array();

        foreach($data as $key=>$val){

            $key = str_replace('"',"",$key);

            $key = str_replace("'","",$key);

            if($key == 'slug' && $val == ''){

            }  else {

            $fields .= "`".$key."`,";

            $values .= "?,";

            if(is_array($val)){

                $val = serialize($val);

            }

            if($has_htmlCharacter){

                $formArray[] = htmlspecialchars($val);

            }else{

                $formArray[] = $val;

            }
            }

        }



        $fields = trim($fields,',');

        $values = trim($values,',');



        $sql    =   "INSERT INTO $tableName ($fields) VALUES ($values)";

        $this->dbF->setRow($sql,$formArray);

        if ($this->dbF->rowCount) {

            $lastId = $this->dbF->rowLastId;

            return $lastId; // success

        }else if($this->dbF->hasException){

            return false; //return exception message

        } else {

            return false; //return false, no update Apply...

        }

    }



    /**

     * @param $tableName

     * @param $array

     */

    /** array contain key as field Name, Value for insert */

    public function formUpdate($tableName,$data,$lastId,$UpdateFieldName='id',$htmlCharacter=false)

    {

        $fields = '';

        $values = '';

        $formArray = array();

        foreach ($data as $key => $val) {

            $key = str_replace('"', "", $key);

            $key = str_replace("'", "", $key);

            $fields .= "`" . $key . "` = ?,";

            if (is_array($val)) {

                $val = serialize($val);
            }else{	
                $val = ($val);	
            	

            }

            if ($htmlCharacter) {

                $formArray[] = htmlspecialchars($val);

            } else {

                $formArray[] = $val;

            }

        }



        $fields = trim($fields, ',');

        $values = trim($values, ',');



        $sql = "UPDATE $tableName SET $fields WHERE $UpdateFieldName = ?";

        $formArray[] = $lastId;

        $this->dbF->setRow($sql, $formArray);

        if ($this->dbF->rowCount) {

            return true; //success

        }else if($this->dbF->hasException){

            return false; //return exception message

        }else{

            return false; //return false, no update Apply...

        }

    }



} // trait end



?>