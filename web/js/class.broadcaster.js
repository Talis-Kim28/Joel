/**
 * Event Broadcaster
 * 
 * this class provides an event broadcaster to add listener and fire events with
 * to listeners
 * 
 * @author florian bosselmann <bosselmann@gmail.com>
 */
 var Broadcaster = new Class({
	listener:[],
	name:'broadcast',
	debug:true,
	
	initialize:function() {
		
	},
	
	addListener:function(o) {
		if(typeof o.events == 'function') {
			this.listener.push(o);
		}
	},
	
	removeListener:function(object, i) {
		if(index = this.listener.indexOf(object)) {
			this.listener.splice(index, 1);
		}
	},
	
	getListener:function() {
		this.listener.each(function(l) {
			console.log(l);
		});
	},
	
	broadcastMessage:function(func, param) {
		var parent = this;
		//if(this.debug) { 
			console.log(this.name+":"+func); 
		//}

		this.listener.each(function(e,i) {
			if(typeof e !== 'undefined') {
				
				e.events(func, param);
			}
		});
	}
});
