class Utils{
    static req(murl, mdata, method, donefunc){
        let mmethod = typeof(method) == 'undefined' ? 'post' : method 

        let abslink = 'http://localhost:8080' + murl;

        let req = $.ajax({
            url: 'http://localhost/wordpress/wp-admin/admin-ajax.php',
            method: "POST",
            data: {
                'action': 'model_auth',
                'method': mmethod,
                'ref_url': abslink,
                'data':mdata
            },
        });
        req.done(function(msg) {
            console.log(JSON.parse(msg));
            donefunc({"data":JSON.parse(msg)});
        });
        req.fail(function(jqXHR, textStatus ) {
            console.log( "Request failed: " + textStatus);
        });
        
    }

    //This only returns the error
    static c_validator(datapreset, data){
        let error = '-';
        for(const key in datapreset){
            if (key in data){
                const d = data[key];
                let runval = datapreset[key](d);
                if (runval === true && typeof(d) !== 'object'){
                    datapreset[key] = d
                }else{
                    error = runval;
                    break;
                }
            }else{
                if (datapreset[key]('') === true){
                    datapreset[key] = '';
                }else{
                    error = key + ' not set';
                }
            }
        }
        return error;
    }

    //Use the return value of this
    static u_validator(datapreset, data){
        let error = '-';
        let bapdata = {};
        for(const key in data){
            
            if (key in datapreset){
                const d = data[key];
                let runval = datapreset[key](d);
                if (runval === true){
                    bapdata[key] = d
                }else{
                    error = runval;
                    break;
                }
            }
        }
        if (error == '-'){
            return bapdata;
        }
        return error;
    }

}
class Usermanager{
    datapreset = {
        username: function(data){
            if (data.length > 3){
                return true;
            }else{
                return 'Name cannot be shorter than 3 chars';
            }
        },
        password:function(data){
            if (data.length > 3){
                return true;
            }else{
                return 'Password cannot be shorter than 3 chars';
            }
        },
        option: (t)=>{return true}
    }
    createUser(data, donefunc){
        let error = Utils.c_validator(this.datapreset, data);
        if (error == '-'){
            return (Utils.req('/users', this.datapreset, 'post', donefunc));
        }else{
            alert(error);
        }
    }
    updateUser(data, donefunc){
        let ret = Utils.u_validator(this.datapreset, data);
        if (typeof(ret) === 'object'){
            return (Utils.req('/users', ret, 'post', donefunc));
        }else{
            alert(ret);
        }
    }

    validateUser(data, donefunc){
        let ret = Utils.c_validator(this.datapreset, data);
        if (ret = '-'){
            return (Utils.req('/users/validate', this.datapreset, 'post', donefunc));
        }else{
            alert(ret);
        }
    }

    
}
class Cakemanager{
    datapreset = {
        name: function(data){
            if (data.length > 3){
                return true;
            }else{
                return 'Name cannot be shorter than 3 chars';
            }
        },
        price: function(data){
            if (typeof(data) === 'number'){
                return true;
            }else{
                return 'Please enter a real amount in digit';
            }
        },
        type: function(data){
            if (data.length > 3){
                return true;
            }else{
                return 'Type cannot be shorter than 3 chars';
            }
        },
        recipe: function(data){
            if (data.length > 3){
                return true;
            }else{
                return 'Recipe cannot be shorter than 3 chars';
            }
        },
        
        option: (t)=>{return true}
    }
    createCake(data, donefunc){
        let error = Utils.c_validator(this.datapreset, data);
        if (error == '-'){
            return (Utils.req('/cakes', this.datapreset, 'post', donefunc));
        }else{
            alert(error);
        }
    }
    updateCake(id, data, donefunc){
        let ret = Utils.u_validator(this.datapreset, data);
        if (typeof(ret) === 'object'){
            console.log(ret);
            return (Utils.req('/cakes/update/'+id, ret, 'post', donefunc));
        }else{
            alert(ret);
        }
    }
    findAllCake(donefunc){
        return (Utils.req('/cakes', {}, 'get', donefunc));
    }
    deleteCake(id, donefunc){
        return (Utils.req('/cakes/delete/'+ id, {}, 'post', donefunc));
    }

    findCake(data, donefunc){
        // data= {
        //     'key':'key',
        //     'value':'value'
        // }
        return (Utils.req('/cakes/findwhere', data, 'post', donefunc));
    }
    searchCake(data, donefunc){
        // data= {
        //     'key':'key',
        //     'search':'search'
        // }
        if (data['search'] != ''){
            return (Utils.req('/cakes/findlike', data, 'post', donefunc));
        }else{
            alert("Enter a search query");
        }
    }

    
}

console.log("Added");