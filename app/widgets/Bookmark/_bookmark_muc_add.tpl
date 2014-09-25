<div class="popup" id="bookmarkmucadd">
    <form name="bookmarkmucadd">
        <fieldset>
            <legend>{$c->__('chatroom.add')}</legend>
            
            <div id="bookmarkmucadderror"></div>
            <div class="element large mini">
                <input name="jid" placeholder="{$c->__('chatroom.id')}"/>
            </div>
            <div class="element large mini">
                <input name="name" placeholder="{$c->__('chatroom.name')}"/>
            </div>
            <div class="element large mini">
                <input name="nick" placeholder="{$c->__('chatroom.nickname')}"/>
            </div>
            <div class="element large mini">
                <label>{$c->__('chatroom.autojoin_label')}</label>
                <div class="checkbox">
                    <input type="checkbox" id="autojoin" name="autojoin"/>
                    <label for="autojoin"></label>
                </div>
            </div>
        </fieldset>
        <div class="menu">
            <a 
                class="button icon yes black merged left"
                onclick="{$submit}"
            >
                {$c->__('button.add')}
            </a><a 
                class="button icon no black merged right" 
                onclick="movim_toggle_display('#bookmarkmucadd')"
            >
                {$c->__('button.close')}
            </a>
        </div>
    </form>
</div>
