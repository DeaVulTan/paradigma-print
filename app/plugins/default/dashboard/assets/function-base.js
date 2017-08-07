function in_array(needle, strict) {
    for(var i = 0; i < this.length; i++) {
        if(strict) {
            if(this[i] === needle) {
                return true;
            }
        } else {
            if(this[i] == needle) {
                return true;
            }
        }
    }
    return false;
}
Array.prototype.in_array = in_array;
