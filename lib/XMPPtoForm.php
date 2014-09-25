<?php
class XMPPtoForm{
    private $fieldset;
    private $xmpp;
    private $html;
    
    public function __construct(){
        $this->fieldset = 0;
        $this->html = '';
        $this->xmpp = '';
    }
    
    public function getHTML($xmpp){
        $this->setXMPP($xmpp);
        $this->create();
        return $this->html;
    }
    
    public function setXMPP($xmpp){
        $this->xmpp = $xmpp;
    }
    
    public function create(){
        $this->xmpp = str_replace('xmlns=', 'ns=', $this->xmpp);
        $x = new SimpleXMLElement($this->xmpp);

        foreach($x->children() as $element){

            switch($element->getName()){
                case "title":
                    $this->outTitle($element);
                    break;
                case "instructions":
                    $this->outP($element);
                    break;
                case "field":
                    if($element['type'] != 'hidden' && $element['type'] != 'fixed')
                        $this->html .='<div class="element">';
                    switch($element['type']){
                        case "boolean":    
                            $this->outCheckbox($element);
                            break;
                        case "fixed":    
                            $this->outBold($element);
                            break;
                        case "text-single":
                            $this->outInput($element, "", "");
                            break;
                        case "text-multi":    
                            $this->outTextarea($element);
                            break;
                        case "text-private":
                            $this->outInput($element, "password", "");
                            break;
                        case "hidden":
                            $this->outHiddeninput($element);
                            break;
                        case "list-multi":
                            $this->outList($element, "multiple");
                            break;
                        case "list-single":
                            $this->outList($element, "");
                            break;
                        case "jid-multi":
                            $this->outInput($element, "email", "multiple");
                            break;
                        case "jid-single":
                            $this->outInput($element, "email", "");
                            break;
                        default:
                            $this->html .= "";
                    }
                    if($element['type'] != 'hidden')
                        $this->html .='</div>';
                    break;
                case 'url':
                    
                    break;
                /*XML without <x> element*/
                case 'username':
                case 'email':
                case 'password':
                    $this->html .='<div class="element">';
                        $this->outGeneric($element->getName());
                    $this->html .='</div>';
                    break;
                default: 
                    $this->html .= "";
            }
        }
        if($this->fieldset>0){ 
            $this->html .= '</fieldset>';
        }
    }

    private function outGeneric($s){
        $this->html .= '
            <label for="'.$s.'">'.
                $s.'
            </label>
            <input id="'.$s.'" name="generic_'.$s.'" type="'.$s.'" required/>';
    }
    private function outTitle($s){
        $this->html .= '
            <h2>'.$s.'</h2>';
    }
    
    private function outP($s){
        $this->html .= '
            <p>'.$s.'</p>';
    }
    
    private function outUrl($s) {
        $this->html .= '
            <a href="'.$s->getName().'">'.$s->getName().'</a>';
    }
    
    private function outBold($s){
        if($this->fieldset > 0){
            $this->html .= '</fieldset>';
        }
        $this->html .= '<fieldset><legend>'.$s->value.'</legend><br />';
        $this->fieldset ++;
    }

    private function outCheckbox($s){        
        $this->html .= '
            <label for="'.$s['var'].'">';
                if($s['label']==null){
                    $this->html .= $s['var'];
                }
                else{
                    $this->html .= $s['label'];
                }
        $this->html .= '
            </label>';
            
        $this->html .= '
            <input 
                id="'.$s['var'].'" 
                name="'.$s['var'].'" 
                xmpptype="'.$s['type'].'"
                xmpplabel="'.$s['label'].'"
                type="checkbox" '.$s->required;
            if($s->value == "true" || $s->value == "1")
                $this->html .= ' checked';
        $this->html .= '/>';
        
    }
    
    private function outTextarea($s){
        $this->html .= '
            <label for="'.$s["var"].'">'.$s["label"].'</label>
            <textarea 
                id="'.$s["var"].'" 
                name="'.$s["var"].'" 
                xmpptype="'.$s['type'].'"
                xmpplabel="'.$s['label'].'"
                required="'.$s->required.'">';
                
        foreach($s->children() as $value){
            if($value->getName() == "value"){
                $this->html .= $value;
            }
        }
        $this->html .= '</textarea>';
    }
    
    private function outInput($s, $type, $multiple){
        if($s->required)
            $req = 'required';
        $this->html .= '
            <label for="'.$s["var"].'">'.$s["label"].'</label>
            <input id="'.$s["var"].'" name="'.$s["var"].'" value="';
            
            foreach($s->children() as $value){
                if($value->getName() == "value"){
                    $this->html .= $value;
                }
            }

        $this->html .= '"';

        if($s['var'] == 'username')
            $this->html .= ' pattern="[a-z0-9_-]*" ';
            
        $this->html .= ' 
            type="'.$type.'" 
            title="'.$s->desc.'" 
            xmpptype="'.$s['type'].'"
            xmpplabel="'.$s['label'].'"
            '.$multiple.' '.$req.'/>';
    }
    
    private function outHiddeninput($s){
        $this->html .= '
            <input type="hidden" name="'.$s["var"].'" value="'.$s->value.'" />';
    }
    
    private function outList($s, $multiple){
        $this->html .= '
            <label for="'.$s["var"].'">'.$s["label"].'</label>
            <div class="select">
                <select 
                    xmpptype="'.$s['type'].'"
                    xmpplabel="'.$s['label'].'"
                    id="'.$s["var"].'" 
                    name="'.$s['var'].'" '.$multiple.' '.$s->required.'>';
        
        if(count($s->xpath('option')) > 0){
            foreach($s->option as $option){
                $this->html .= '
                    <option value="'.$option->value.'"';
                    if(in_array((string)$option->value, $s->xpath('value')))
                        $this->html .= ' selected';
                $this->html .= '>'.
                        $option->value.'
                    </option>';
            }
        }
        else{
            foreach($s->value as $option){
                $this->html .= '
                    <option value="'.$option['label'].'" selected>'.
                        $option.'
                    </option>';
            }
        }
        
        $this->html .= '
                </select>
            </div>';
    }
}

class FormtoXMPP{
    private $stream;
    private $inputs;
    private $dataform;
    
    public function __construct(){
        $this->stream = '';
        $this->inputs = array();
        $this->dataform = true;
    }
    
    public function getXMPP($stream, $inputs){
        $this->setXMPP($stream);
        $this->setInputs($inputs);
        $this->create();
        return $this->stream;
    }
    
    public function setXMPP($stream){
        $this->stream = new SimpleXMLElement($stream);
    }
    public function setInputs($inputs){
        $this->inputs = $inputs;
    }
    
    public function setDataformOff() {
        $this->dataform = false;
    }
    
    public function create(){
        switch($this->stream->getName()){
            case "stream": 
                $node = $this->stream->iq->query->x;
                break;
            case "pubsub":
                $node = $this->stream->configure->x;
                break;
        }
        foreach($this->inputs as $key => $value) {
            if($value === '' && $this->stream->getName() == "stream") {
                RPC::call('movim_reload', Route::urlize('account','datamissing'));
                RPC::commit();
                 exit;
            } elseif(substr($key, 0, 8) == 'generic_') {
                $key = str_replace('generic_', '', $key);
                if (!is_string($value)) {
                    $value = $value->value;
                }
                $node->addChild($key, $value);
            } elseif($value->attributes) {
                $field = $node->addChild('field');
                if($value == 'true')
                    $value = '1';
                if($value == 'false')
                    $value = '0';
                    
                $field->addChild('value', trim($value->value));
                if(isset($value->attributes->required))
                    $field->addChild('required', '');
                $field->addAttribute('var', $value->attributes->name);
                $field->addAttribute('type', $value->attributes->xmpptype);                
                $field->addAttribute('label', $value->attributes->xmpplabel);     
                           
            } else{
                $field = $node->addChild('field');

                if($value == 'true' || $value === 1)
                    $value = '1';
                if($value == 'false' || $value === 0)
                    $value = '0';
                    
                $field->addChild('value', trim($value));
                $field->addAttribute('var', trim($key));
            }
        }
    }
}
?>
