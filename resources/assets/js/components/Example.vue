<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>
                     <div id="name-form">
                        <label for="name">Enter name:</label>
                        <input type="text" v-model="name" id="name" name="name" />
                        <p>{{ name }}</p>
                        <button @click="clearData()" class="btn btn-default">Clear</button>
                     </div>
                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">List the Beatles</div>
                    <div>
                        <ul v-for="member in beatles">
                            <li>{{ member }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    var givenName = 'Bill';
    var bandMembers = ['aaa', 'bbb', 'ccc', 'ddd'];
    export default {
        data : function(){
            return {
                name: '',
                beatles: []
            }
        },
        methods : {
          loadData : function(){
            this.name = givenName;
          },
            loadData1 : function () {
                this.beatles = bandMembers;
            },
          clearData : function(){
            this.name = '';
          }
        },
        mounted() {
            this.loadData();
            this.loadData1();
            console.log('Component ready.')
        }
    }
</script>
