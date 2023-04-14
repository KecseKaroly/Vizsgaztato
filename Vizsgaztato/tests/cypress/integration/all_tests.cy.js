describe('Authentication', () => {
    beforeEach(() =>  {
    });
    it('registers a teacher', () => {
        cy.refreshDatabase();
        cy.login({username: 'admin', auth: '9'});
        cy.visit('/');
        cy.contains('Tanár hozzáadása').click();
        cy.get('#name').type('Minta Tanár');
        cy.get('#username').type('mintatanar');
        cy.get('#email').type('mintatanar@examonline.com');
        cy.get('#password').type('mintatanar');
        cy.get('#password-confirm').type('mintatanar');
        cy.get('#register').click();

        cy.contains('Tanár sikeresen rögzítve!');
    });
    it('registers a teacher with the same emails', () => {
        cy.login({username: 'admin', auth: '9'});
        cy.visit('/');
        cy.contains('Tanár hozzáadása').click();
        cy.get('#name').type('Minta Tanár');
        cy.get('#username').type('mintatanar');
        cy.get('#email').type('mintatanar@examonline.com');
        cy.get('#password').type('mintatanar');
        cy.get('#password-confirm').type('mintatanar');
        cy.get('#register').click();

        cy.contains('A megadott email cím már foglalt!');
    });
    it('registers a teacher with different passwords', () => {
        cy.login({username: 'admin', auth: '9'});
        cy.visit('/');
        cy.contains('Tanár hozzáadása').click();
        cy.get('#name').type('Minta Tanár');
        cy.get('#username').type('mintatanar');
        cy.get('#email').type('mintatanar@examonline.com');
        cy.get('#password').type('mintatanar1');
        cy.get('#password-confirm').type('mintatanar2');
        cy.get('#register').click();

        cy.contains('A jelszavak nem egyeznek!');
    });
    it('registers as a student with required validation error', () => {
        cy.visit('/register');
        cy.contains('Sütik engedélyezése').click();
        cy.get('#register').click();
        cy.contains('Név megadása kötelező!');
        cy.contains('Felhasználónév megadása kötelező!');
        cy.contains('Email megadása kötelező!');
        cy.contains('Jelszó megadása kötelező!');
        cy.contains('A felhasználási feltételek elfogadása kötelező!');
    });
    it('registers as a student', () => {
        cy.visit('/register');
        cy.contains('Sütik engedélyezése').click();
        cy.get('#name').type('Minta Márton');
        cy.get('#username').type('mintamarton');
        cy.get('#email').type('mintamarton@vmi.com');
        cy.get('#password').type('mintamarton');
        cy.get('#password-confirm').type('mintamarton');
        cy.get('#acceptTOS').check();
        cy.get('#register').click();
    });
});
describe('Groups', () => {
    it('creates a group with error', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups');
        cy.get('#createGroup').click();
        cy.get('#name').type('12');
        cy.get('#create').click();
        cy.contains('Hiba');
    });
    it('mintamarton creates a group', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups');
        cy.get('#createGroup').click();
        cy.get('#name').type('Minta Marton csoportja');
        cy.get('#create').click();
        cy.contains('Csoport sikeresen létrehozva!');
    });
    it('mintaantal creates a group', () => {
        cy.login({username: 'mintaantal', name: 'Minta Antal', email: 'mintaantal@vmi.com', auth: '1'});
        cy.visit('/groups');
        cy.get('#createGroup').click();
        cy.get('#name').type('Minta Antal csoportja');
        cy.get('#create').click();
        cy.contains('Csoport sikeresen létrehozva!');
    });
    it('mintamarton updates a group', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups');
        cy.contains('Minta Marton csoportja').click();
        cy.contains('Csoport Szerkesztése').click();
        cy.get('#group_name').type('a');
        cy.get('.updateBtn').click();
        cy.get('.swal2-confirm').click();
        cy.contains('Csoport sikeresen módosítva!');
    });
    it('gets View Policy error', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups/2');
        cy.contains('Nem engedélyezett művelet! Nem tagja ennek a csoportnak!');
    });
    it('gets Update Policy error', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups/2/edit');
        cy.contains('Nem engedélyezett művelet! Nem Önhöz tartozik ez a csoport!');
    });
    it('mintamarton invites mintaantal and tesztelek to group', () => {
        cy.create('App\\Models\\User', {email: 'tesztelek@vmi.com', username: 'tesztelek', name: 'Teszt Elek', auth: '1'});
        cy.login({username: 'mintamarton'});
        cy.visit('/groups');
        cy.contains('Minta Marton csoportja').click();

        cy.get('#emailInputField').type('mintaantal');
        cy.contains('mintaantal@vmi.com').click();

        cy.get('#emailInputField').type('tesztelek');
        cy.contains('tesztelek@vmi.com').click().then(() => {
            cy.contains('Meghívás').click();
            cy.contains('Sikeres rögzítés');
        });
    });
    it('tesztelek declines invrequest', () => {
        cy.login({username: 'tesztelek'});
        cy.visit('/groups');
        cy.contains('Meghívók').click();
        cy.get('.declineInvRequest').click();
        cy.contains('Meghívás elutasítva');
    });
    it('mintaantal accepts invrequest', () => {
        cy.login({username: 'mintaantal'});
        cy.visit('/groups');
        cy.contains('Meghívók').click();
        cy.get('.acceptInvRequest').click();
        cy.visit('/groups');
        cy.contains('Minta Marton csoportjaa');
    });
    it('submits wrong group inv code', () => {
        cy.login({username: 'tesztelek'});
        cy.visit('/groups');
        cy.get('#invCode').type("valami");
        cy.contains('Jelentkezés').click();
        cy.contains('A megadott kódhoz nem tartozik csoport!');
    });
    it('submits group inv code', () => {
        cy.login({username: 'tesztelek'});
        cy.visit('/groups');
        cy.php(`App\\Models\\group::first()`).then(group => {
            cy.get('#invCode').type(group.invCode);
            cy.contains('Jelentkezés').click();
            cy.contains('Jelentkezési kérelem sikeresen rögzítve!');
            cy.get('.swal2-confirm').click();
            cy.contains('Jelentkezés').click();
            cy.contains('Már rögzített csatlakozási kérelmet ebbe a csoportba!');
        });
    });
    it('accepts group inv code', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups');
        cy.contains('Kérelmek').click();
        cy.get('.acceptJoinRequest').click();
        cy.contains('Csatlakozási kérelem elfogadva');
        cy.visit('/groups/1');
        cy.contains('Teszt Elek');
    });

    it('mintaantal leaves from first group', () => {
        cy.login({username: 'mintaantal'});
        cy.visit('/groups/1');
        cy.get('.leaveFromGroup').click();
        cy.get('.swal2-confirm').click();
        cy.contains('Minta Marton csoportjaa').should('not.exist');
    });

    it('kicks tesztelek from first group', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups/1');
        cy.contains('Sütik engedélyezése').click();
        cy.get('.fa-ban').click();
        cy.get('.swal2-confirm').click();
        cy.contains('Sikeres törlés');
        cy.contains('Teszt Elek').should('not.exist');
    });

    it('mintaantal deletes group', () => {
        cy.login({username: 'mintaantal'});
        cy.visit('/groups/2');
        cy.get('.deleteGroupBtn').click();
        cy.get('.swal2-confirm').click();
        cy.contains('Csoport sikeresen törölve!');
        cy.contains('Minta Antal Csoportja').should('not.exist');
    });
});
describe('Courses', () => {
    it('creates a course with validation error', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Kurzus létrehozása').click();
        cy.get('#name').type('12');
        cy.get('#goal').type('A kurzus céljának hosszú leírása...');
        cy.contains('Létrehozás').click();
        cy.contains('Hiba');
        cy.get('#name').type('123');
        cy.contains('Létrehozás').click();
        cy.contains('Kurzus sikeresen mentve!');
    });
    it('updates the course', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('123').click();
        cy.contains('Kurzus Szerkesztése').click();
        cy.get('#title').clear().type('Minta kurzus');
        cy.get('#goal').type('frissítve');
        cy.contains('Módosít').click();
        cy.contains('Sikeres módosítás');
    });
    it('adds members to the course', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Tagok').click();
        cy.contains('Diák hozzáadása').click();

        cy.get('#emailInputField').type('tesztelek');
        cy.contains('tesztelek').click();

        cy.get('#emailInputField').type('mintamarton');
        cy.contains('mintamarton').click().then(() => {
            cy.contains('Meghívás').click();
            cy.contains('Minta Márton');
            cy.contains('Teszt Elek');
        });

        cy.logout();
        cy.login({username: 'mintamarton'});
        cy.visit('/courses');
        cy.contains('Minta kurzus');

        cy.logout();
        cy.login({username: 'tesztelek'});
        cy.visit('/courses');
        cy.contains('Minta kurzus');
    });
    it('adds groups to the course', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Tagok').click();
        cy.contains('Csoportok kezelése').click();

        cy.get('#groupInputField').type('Minta Marton');
        cy.contains('Minta Marton').click().then(() => {
            cy.contains('Mentés').click();
            cy.contains('Minta Marton csoportja');
        });

        cy.logout();
        cy.login({username: 'mintamarton'});
        cy.visit('/courses');
        cy.contains('Minta kurzus');
    });
    it('kicks from group but should be visible beacuse group is added', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Tagok').click();
        cy.get('#course_user-3 > :nth-child(3) > .py-1 > form > .removeUserFromCourse > .fa-stack > .fa-ban').click();
        cy.get('.swal2-confirm').click();
        cy.contains('Sikeres törlés');

        cy.logout();
        cy.login({username: 'mintamarton'});
        cy.visit('/courses');
        cy.contains('Minta kurzus');
    });

    it('kicks from group and should not be visible', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Tagok').click();
        cy.get('#course_user-2 > :nth-child(3) > .py-1 > form > .removeUserFromCourse > .fa-stack > .fa-ban').click();
        cy.get('.swal2-confirm').click();
        cy.contains('Sikeres törlés');

        cy.logout();
        cy.login({username: 'tesztelek'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').should('not.exist');
    });

    it('gets an View Policy error because not member', () => {
        cy.login({username: 'tesztelek'});
        cy.visit('/courses/1');
        cy.contains('Jogosulatlan a kurzushoz!');
    });

    it('gets an Update Policy error because not owner', () => {
        cy.login({username: 'tesztelek'});
        cy.visit('/courses/1/edit');
        cy.contains('Nem Ön a kurzus készítője, így nem módosíthatja azt!');
    });
    it('should not see modifier buttons', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Kurzus Törlése').should('not.exist');
        cy.contains('Kurzus Szerkesztése').should('not.exist');
        cy.contains('Tagok').click();

        cy.contains('Diákok hozzáadása').should('not.exist');
        cy.contains('Csoportok kezelése').should('not.exist');
    });

    it('deletes a course', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Kurzus Törlése').click();
        cy.get('.swal2-confirm').click();
        cy.contains('Kurzus sikeresen törölve!');
    });

    it('creates a course', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Kurzus létrehozása').click();
        cy.get('#name').type('Minta kurzus');
        cy.get('#goal').type('A kurzus céljának hosszú leírása...');
        cy.contains('Létrehozás').click();
        cy.contains('Kurzus sikeresen mentve!');
        cy.get('.swal2-confirm').click();
        cy.contains('Tagok').click();
        cy.contains('Diák hozzáadása').click();

        cy.get('#emailInputField').type('tesztelek');
        cy.contains('tesztelek').click();

        cy.get('#emailInputField').type('mintaantal');
        cy.contains('mintaantal').click().then(() => {
            cy.contains('Meghívás').click();
            cy.contains('Minta Antal');
            cy.contains('Teszt Elek');
        });

        cy.contains('Csoportok kezelése').click();

        cy.get('#groupInputField').type('Minta Marton');
        cy.contains('Minta Marton').click().then(() => {
            cy.contains('Mentés').click();
            cy.contains('Minta Marton csoportja');
        });
    });
});
describe('Modules', () => {
    it('tries to creates a module with validation error', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Modulok').click();
        cy.contains('Modul hozzáadása').click();

        cy.get('#title').type('12');
        cy.get('#topic').type('12');

        cy.contains('Sütik engedélyezése').click();
        cy.contains('Létrehozás').click();
        cy.contains('Hiba');
    });

    it('creates a module with HELP', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Modulok').click();
        cy.contains('Modul hozzáadása').click();

        cy.get('#title').type('Minta modul CÍME');
        cy.get('#topic').type('Minta modul TÉMAKÖRE');

        cy.wait(5000);

        cy.contains('Létrehozás').click();
        cy.contains('Modul sikeresen létrehozva!');
    });

    it('views a module as a student', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/courses');
        cy.contains('Minta kurzus').click();
        cy.contains('Modulok').click();
        cy.contains('Modul hozzáadása').should('not.exist');
        cy.get('.bg-yellow-50').should('not.exist');
        cy.get('.bg-red-50').should('not.exist');
        cy.get('.text-2xl').click();
        cy.contains('Minta modul CÍME');
    });

    it('updates a module', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Sütik engedélyezése').click();
        cy.contains('Minta kurzus').click();
        cy.contains('Modulok').click();
        cy.get('.bg-yellow-50').click();
        cy.get('#topic').type('_1')
        cy.contains('Módosítás').click();
        cy.contains('Modul sikeresen frissítve!');
    });
});
describe('Quizzes', () => {
    it('creates a quiz for a module', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Sütik engedélyezése').click();
        cy.contains('Minta kurzus').click();
        cy.contains('Modulok').click();
        cy.get('.text-2xl').click();
        cy.contains('Kvíz hozzáadása').click();

        cy.get('#titleOfTestAttempt').type('IGAZ');
        cy.contains('A teszt címének hossza legalább 5 karakter kell, hogy legyen!');
        cy.get('#titleOfTestAttempt').type(' HAMIS KVÍZ');

        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Igaz/Hamis');
        cy.get('#questionText_0').type('IGAZ');
        cy.contains('A kérdés szövegének hossza legalább 5 karakter kell, hogy legyen!');
        cy.get('#questionText_0').type(' Hamis kérdés');
        cy.wait(500);
        cy.get('#option_0_0').check();

        cy.wait(500);
        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Egy megoldásos');
        cy.get('#questionText_0').type('Egy megoldásos kérdés');
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#optionText_0_0').type('Hamis');
        cy.get('#optionText_0_1').type('Hamis');
        cy.get('#optionText_0_2').type('Igaz');
        cy.contains('A válaszlehetőség szövege legalább 5 karakter hosszúnak kell lennie!');
        cy.get('#optionText_0_2').type(' válasz');
        cy.get('#optionText_0_3').type('Hamis');
        cy.wait(500);
        cy.get('#option_0_2').check();

        cy.wait(500);
        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Több megoldásos');
        cy.get('#questionText_0').type('Több megoldásos kérdés');
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#optionText_0_0').type('Hamis');
        cy.get('#optionText_0_1').type('Hamis');
        cy.get('#optionText_0_2').type('Igaz válasz');
        cy.get('#optionText_0_3').type('Hamis');
        cy.get('#optionText_0_4').type('Igaz válasz');
        cy.wait(500);
        cy.get('#option_0_2').check();
        cy.get('#option_0_4').check();

        cy.wait(500);
        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Sorrend');
        cy.get('#questionText_0').type('Tegye a megfelelő sorrendbe az alábbiakat!');
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#optionText_0_0').type('1 (EGY)');
        cy.get('#optionText_0_1').type('2 (KETTŐ)');
        cy.get('#optionText_0_2').type('3 (HÁROM)');
        cy.get('#optionText_0_3').type('4 (NÉGY)');
        cy.wait(100);
        cy.contains('Mentés').click();
        cy.wait(500);
    });

    it('updates a quiz', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Sütik engedélyezése').click();
        cy.contains('Minta kurzus').click();
        cy.contains('Kvízek').click();
        cy.get('.bg-yellow-50').click();

        cy.get('#optionText_1_1').type('ról ez is igaz lett');
        cy.get('#option_1_1').check();

        cy.get('#optionText_2_2').type('ról igaz lett');
        cy.get('#option_2_2').check();

        cy.get('#questionText_3').clear().type('Igaz HAMIS kérdés');
        cy.get('#option_3_0').check();

        cy.contains('Mentés').click();

        cy.wait(500);
    });
});
describe('Exams', () => {
    it('creates an exam', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Sütik engedélyezése').click();
        cy.contains('Minta kurzus').click();
        cy.contains('Vizsga feladatsorok').click();
        cy.contains('Új vizsgasor').click();

        cy.get('#titleOfTestAttempt').type('IGAZ');
        cy.contains('A teszt címének hossza legalább 5 karakter kell, hogy legyen!');
        cy.get('#titleOfTestAttempt').type(' HAMIS KVÍZ');

        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Igaz/Hamis');
        cy.get('#questionText_0').type('IGAZ');
        cy.contains('A kérdés szövegének hossza legalább 5 karakter kell, hogy legyen!');
        cy.get('#questionText_0').type(' Hamis kérdés');
        cy.wait(500);
        cy.get('#option_0_0').check();

        cy.wait(500);
        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Egy megoldásos');
        cy.get('#questionText_0').type('Egy megoldásos kérdés');
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#optionText_0_0').type('Hamis');
        cy.get('#optionText_0_1').type('Hamis');
        cy.get('#optionText_0_2').type('Igaz');
        cy.contains('A válaszlehetőség szövege legalább 5 karakter hosszúnak kell lennie!');
        cy.get('#optionText_0_2').type(' válasz');
        cy.get('#optionText_0_3').type('Hamis');
        cy.wait(500);
        cy.get('#option_0_2').check();

        cy.wait(500);
        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Több megoldásos');
        cy.get('#questionText_0').type('Több megoldásos kérdés');
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#optionText_0_0').type('Hamis');
        cy.get('#optionText_0_1').type('Hamis');
        cy.get('#optionText_0_2').type('Igaz válasz');
        cy.get('#optionText_0_3').type('Hamis');
        cy.get('#optionText_0_4').type('Igaz válasz');
        cy.wait(500);
        cy.get('#option_0_2').check();
        cy.get('#option_0_4').check();

        cy.wait(500);
        cy.contains('Új kérdés').click();
        cy.get('#typeSelector_0').select('Sorrend');
        cy.get('#questionText_0').type('Tegye a megfelelő sorrendbe az alábbiakat!');
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#addOptionToQuestion_0').click();
        cy.get('#optionText_0_0').type('1 (EGY)');
        cy.get('#optionText_0_1').type('2 (KETTŐ)');
        cy.get('#optionText_0_2').type('3 (HÁROM)');
        cy.get('#optionText_0_3').type('4 (NÉGY)');
        cy.wait(100);
        cy.contains('Mentés').click();
        cy.wait(500);
    });

    it('updates an exam', () => {
        cy.login({username: 'mintatanar'});
        cy.visit('/courses');
        cy.contains('Sütik engedélyezése').click();
        cy.contains('Minta kurzus').click();
        cy.contains('Vizsga feladatsorok').click();
        cy.get('.bg-yellow-50').click();

        cy.get('#optionText_1_1').type('ról ez is igaz lett');
        cy.get('#option_1_1').check();

        cy.get('#optionText_2_2').type('ról igaz lett');
        cy.get('#option_2_2').check();

        cy.get('#questionText_3').clear().type('Igaz HAMIS kérdés');
        cy.get('#option_3_0').check();

        cy.contains('Mentés').click();
        cy.wait(500);
    });
});
