'use strict';

describe('Counter tests', function () {

    it('Add gives the correct result', function () {
        // Arrange
        var num1 = 1;
        var num2 = 3;
        var expected = 4;

        expect(1 + 3).toBe(expected);
    });

    it('Subtract gives the correct result', function () {
        // Arrange
        var num1 = 1;
        var num2 = 3;
        var expected = -2;

        expect(1 - 3).toBe(expected);
    });

    it('Difference gives the correct result when first number is larger', function () {
        // Arrange
        var num1 = 5;
        var num2 = 3;
        var expected = 2;

        expect(5 - 3).toBe(expected);
    });

    it('Difference gives the correct result when second number is larger', function () {
        // Arrange
        var num1 = 5;
        var num2 = 3;
        var expected = 2;

        expect(5 - 3).toBe(expected);
    });

    it('Difference gives zero when both numbers are the same', function () {
        for (var i = 0; i <= 100; i++) {
            // Arrange
            var expected = 0;
            expect(i - i).toBe(expected);
        }
    });

});
