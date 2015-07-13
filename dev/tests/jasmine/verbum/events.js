'use strict';

describe('Application events', function() {
    afterEach(function() {
        App.events.reset();
    });

    it('defines `events` object inside app', function() {
        expect(App.events).toBeDefined();
    });

    it('allows to register new event handler and emit it', function() {
        expect(App.events.on).toBeDefined();
        expect(App.events.emit).toBeDefined();

        var spy = jasmine.createSpy('callMeWhenYouAreSober');
        App.events.on('app:event1', spy);
        App.events.emit('app:event1', 'arg1', 'arg2');

        expect(spy).toHaveBeenCalledWith('arg1', 'arg2');
    });
});
