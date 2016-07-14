function releaseCity(k) {
    _rt('#p_city_collation_' + k).set({className: ''});
    _rt('#city_collation_' + k).set({disabled: false});
    _rt('#release_' + k).set({style: {display: 'none'}});
    return false;
}