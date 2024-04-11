var islands = {
    oahu: ["Aiea", "Ewa Beach", "Haleiwa", "Hauula", "Honolulu", "Kaaawa", "Kahuku", "Kailua", "Kaneohe", "Kapolei", "Laie", "Mililani", "Pearl City", "Wahiawa", "Waialua", "Waianae", "Waimanalo", "Waipahu"],
    maui: ["Haiku", "Kihei","Hana", "Kahului", "Kaunakakai", "Kihei", "Kula", "Lahaina", "Lanai City", "Makawao", "Paia", "Pukalani", "Wailuku"],
    kauai: ["Eleele", "Hanalei", "Kalaheo", "Kapaa", "Kekaha", "Kilauea", "Koloa", "Lihue", "Princeville"],
    hawaii: ["Captain Cook", "Hilo", "Holualoa", "Honokaa", "Kailua Kona", "Kamuela", "Keaau", "Kealakekua", "Kurtistown", "Mountain View", "Naalehu", "Pahoa", "Waikoloa"]
};

function islandChanged(){
    var island = $("#island").val();
    var str = '<select name="city" class="form-control select2" id="city">';
    if(typeof islands[island] !== "undefined"){
        for(var i = 0; i < islands[island].length; i++){
            var city = islands[island][i];
            str = str+`<option value="${city}">${city}</option>`;
        }
    }
    str = str+'</select>';
    $("#divCity").html(str);
    $("#city").select2();
}