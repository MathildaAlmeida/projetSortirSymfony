

function lieux()
{
    select = document.getElementById("idville");
    choix = select.selectedIndex;
    valeur = select.options[choix].value;
    axios
        .get('http://127.0.0.1:8000/lieux_ville/'+valeur)
        .then(response => {
            console.log(response)
            const option = document.getElementById("sortie_noLieu");
            const DivOption = document.getElementById("sortie_noLieu");
            option.innerText = '';

            for (let i = 0; i < response['data'].length; i++) {
                var newOption = document.createElement("option");
                var id = response['data'][i]['id'];
                var nomlieu = response['data'][i]['nomLieu'];
                newOption.value = id;
                newOption.innerText = nomlieu;
                DivOption.add(newOption)
            }
            infoslieux()
        })

}
function infoslieux(){
    select = document.getElementById("sortie_noLieu");
    const Divrue = document.getElementById("rue");
    const Divlat = document.getElementById("latitude");
    const Divlong = document.getElementById("longitude");

    choix = select.selectedIndex;
    valeur = select.options[choix].value;
    axios
        .get('http://127.0.0.1:8000/infos_lieux/'+valeur)
        .then(response => {
            console.log(response)

            var rue = response['data'][0]['rue'];
            if(rue == null){
                Divrue.textContent = "Non définie";
            }else{
                Divrue.textContent = rue;
            }

            var lat = response['data'][0]['latitude']
            if(lat == null){
                Divlat.textContent = "Non définie";
            }else{
                Divlat.textContent = lat;
            }


            var long = response['data'][0]['longitude']
            if(long == null){
                Divlong.textContent = "Non définie";

            }else{
                Divlong.textContent = long;
            }
        })
}