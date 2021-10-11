<template>

    <div class="card-block">
        <div class="col-md-3 relative">
            <div class="sidebar-fixed">
                <table class="table table-hover table-striped" v-if="groups">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Group</th>
                            <th class="text-center">Show Childs</th>
                        </tr>
                    </thead>
                    <tbody class="vertical-scroll">
                        <tr :class="['group', key == selected ? 'active' : '']" v-for="(group, key) in groups" @click="changeGroup(group, key)">
                            <td>
                                <span class="tool" :data-tip=" group.comment " tabindex="1"></span>
                                {{ key + 1 }}
                            </td>
                            <td>
                                {{ group.key }}
                            </td>
                            <td class="text-center">
                                <i class="fa fa-arrow-right"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <item-translations  :class="['group-tab', key == selected ? 'show' : 'hide']"
                            :langs="langs"
                            :group="group"
                            v-for="(group, key) in groups">
        </item-translations>
    </div>

</template>

<script>
import { bus } from '../../app';

export default {
    props: ['langs'],
    data(){
        return {
            groups: [],
            items : [],
            loading : false,
            selected: 0,
        }
    },
    mounted(){
        bus.$on('sendTranslations', data => {
            this.groups = data;
            bus.$emit('changeGroup', {group: this.groups[0]})
        });
    },
    methods: {
        changeGroup(group, key){
            bus.$emit('changeGroup', {group: group})
            $('.group').removeClass('active');
            this.selected = key;
        }
    }
}
</script>

<style>
td{ position: relative; }
/*== start of code for tooltips ==*/
.tool {
    cursor: help;
    position: absolute;
    left: 0;
    top: 15px;
    height: 20px;
    z-index: 100;
    font-size: 10px;
    text-align: center;
    color: #FFF;
}

/*== common styles for both parts of tool tip ==*/
.tool::before,
.tool::after {
    left: 50%;
    opacity: 0;
    position: absolute;
    z-index: -100;
}

.tool:hover::before,
.tool:focus::before,
.tool:hover::after,
.tool:focus::after {
    opacity: 1;
    transform: scale(1) translateY(0);
    z-index: 100;
}

/*== pointer tip ==*/
.tool::before {
    border-style: solid;
    border-width: 1em 0.75em 0 0.75em;
    border-color: #3E474F transparent transparent transparent;
    bottom: 100%;
    content: "";
    margin-left: -15px;
    transition: all .35s cubic-bezier(.84,-0.18,.31,1.26), opacity .35s .5s;
    transform:  scale(.6) translateY(-90%);
}

.tool:hover::before,
.tool:focus::before {
    transition: all .35s cubic-bezier(.84,-0.18,.31,1.26) .2s;
}

/*== speech bubble ==*/
.tool::after {
    background: #3E474F;
    border-radius: .35em;
    bottom: 130%;
    color: #FFF;
    content: attr(data-tip);
    margin-left: -50px;
    padding: 5px;
    transition: all .35s cubic-bezier(.84,-0.18,.31,1.26) .2s;
    transform:  scale(.6) translateY(50%);
    width: 120px;
    line-height: 1;
}
.tool:hover::after,
.tool:focus::after  {
    transition: all .35s cubic-bezier(.84,-0.18,.31,1.26);
}
.header-fixed{
    z-index: 200;
}
.group{
    cursor: pointer;
}
.relative{
    position: relative;
}
.sidebar-fixed{
    position: fixed;
    height: 95vh;
    overflow: scroll;
    width: 22%;
    border-right: 1px solid #EEE;
    padding-right: 20px;
    box-shadow: 4px 5px 10px 0 rgba(0,0,0,0.10);
}
.vertical-scroll{
    overflow: scroll;
}
</style>
