describe('Groups', () => {
  it('mintamarton creates a group', () => {
        cy.login({username: 'mintamarton'});
        cy.visit('/groups');
        cy.get('#createGroup').click();
        cy.get('#name').type('Minta Marton csoportja');
        cy.get('#create').click();
        cy.contains('Csoport sikeresen letrehozva!');
  });
}