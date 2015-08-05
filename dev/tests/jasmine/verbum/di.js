'use strict';

describe('Dependency Injection Container', function() {
    beforeAll(function() {
        App.DI.ContainerTest = {};

        App.DI.ContainerTest.ClassA = function() {};
        App.DI.ContainerTest.ClassA.prototype.setDep1 = function(dep1) {
            'inject dep1';
            this.dep1 = dep1;
        };
    });

    afterAll(function() {
        delete App.DI.ContainerTest;
    });

    it('should allow to put objects to it and to clean it up', function() {
        var container = new App.DI.Container();
        var obj1 = {};
        container.put('obj1', obj1);
        expect(container.get('obj1')).toEqual(obj1);

        container.reset();
        expect(container.get('obj1')).toBeUndefined();
    });

    it('should instantiate object and inject dependencies', function() {
        var container = new App.DI.Container();
        var dep1 = {dep1: true};
        container.put('dep1', dep1);

        var objA = container.get(App.DI.ContainerTest.ClassA);
        expect(objA instanceof App.DI.ContainerTest.ClassA).toBeTruthy();
        expect(objA.dep1).toBeDefined();
        expect(objA.dep1).toEqual(dep1);
    });

    it('should instantiate dependent objects and inject them', function() {
        App.DI.ContainerTest.ClassB = function() {};
        App.DI.ContainerTest.ClassB.prototype.setObjA = function(objA) {
            'inject App.DI.ContainerTest.ClassA';
            this.objA = objA;
        };

        App.DI.ContainerTest.ClassC = function() {};
        App.DI.ContainerTest.ClassC.prototype.setObjThatDoesNotExist = function(obj1) {
            'inject App.DI.ContainerTest.ClassThatDoesNotExist';
            this.obj1 = obj1;
        };

        var container = new App.DI.Container();
        var objB = container.get(App.DI.ContainerTest.ClassB);

        expect(objB.objA).toBeDefined();
        expect(objB.objA instanceof App.DI.ContainerTest.ClassA).toBeTruthy();

        expect(function() {
            container.get(App.DI.ContainerTest.ClassC);
        }).toThrow();
    });

    it('should provide methods to inject dependencies of existing objects', function() {
        var a = new App.DI.ContainerTest.ClassA();

        var container = new App.DI.Container();
        var dep1 = {dep1: true};
        container.put('dep1', dep1);

        container.inject(a);
        expect(a.dep1).toEqual(dep1);
    });

    it('should handle cross-dependencies properly', function() {
        App.DI.ContainerTest.ClassB = function() {};
        App.DI.ContainerTest.ClassB.prototype.setObjC = function(objC) {
            'inject App.DI.ContainerTest.ClassC';
            this.objC = objC;
        };

        App.DI.ContainerTest.ClassC = function() {};
        App.DI.ContainerTest.ClassC.prototype.setObjB = function(objB) {
            'inject App.DI.ContainerTest.ClassB';
            this.objB = objB;
        };

        var container = new App.DI.Container();
        expect(function() {
            container.get(App.DI.ContainerTest.ClassB);
        }).toThrow();
    });
});
