2 strategy pattern
 Suppose we have a PaymentService that does a bunch of stuff... including charging people via credit card. But now, we discover that we need to use this exact same class to allow people to pay via PayPal... or via pirate treasure - that sounds more fun.

 Anyways, how can we do that? The strategy pattern! We would allow a new PaymentStrategyInterface object to be passed into PaymentService and then we would call that.

 Next, we would create two classes that implement the new interface: CreditCardPaymentStrategy and PiratesBootyPaymentStrategy. That's it! We now have control of which class we pass in. Yep! We just made part of the code inside PaymentService controllable from the outside.
2.2 passing and using the strategy pattern
2.3 
 - What's great about the "strategy pattern" is that, instead of trying to pass options to Character like $canCastSpells = true to configure the attack, we have full control.

 To prove it, let's add a new character - a mage archer: a legendary character that has a bow and casts spells. Double threat!

 To support this idea of having two attacks, create a new AttackType called MultiAttackType. Make it implement the AttackType interface and go to "Implement Methods" to add the method.

 In this case, I'm going to create a constructor where we can pass in an array of \attackTypes. To help out my editor
4.2 creational method - builder pattern
5.1 Use character builder class
5.2 ... notation accept multiple attack types instead of one atack type
 $attackTypes = array_map(
  fn(string $attackType) => $this->createAttackType($attackType),
  $this->attackTypes
 );
7.1 observer pattern
 Creating the Observer Interface
 - Ok, step one to this pattern is to create an interface that all the observers will implement. For organization's sake, I'll create an Observer/ directory. Inside, add a new PHP class, make sure "Interface" is selected, and call it, how about, GameObserverInterface... since these classes will be "observing" something related to each game. FightObserverInterface would also have been a good name

 Inside we just need one public method. We can call it anything: how about onFightFinished()

 Why do we need this interface? Because, in a minute, we're going to write code that loops over all of the observers inside of GameApplication and calls a method on them. So... we need a way to guarantee that each observer has a method, like onFightFinished(). And we can actually pass onFightFinished() whatever arguments we want. Let's pass it a FightResult argument because, if I want to run some code after a fight finishes, it'll probably be useful to know the result of that fight. I'll also add a void return type

 Adding the Subscribe Code
 - Okay, step two: We need a way for every observer to subscribe to be notified on GameApplication. To do that, create a public function called, how about, subscribe(). You can name this anything. This is going to accept any GameObserverInterface, I'll call it  and it will return void. I'll fill in the logic in a moment

 The second part, which is optional, is to add a way to unsubscribe from the changes. Copy everything we just did... paste... and change this to unsubscribe()
7.2 Finish our base class
8.1 creating an observer that will calculate how much XP the winner should earn and whether or not the character should level up.
8.2 instantiate the observer and make it subscribe to the subject: GameApplication
9.1
 App\GameApplication:
    calls:
      - subscribe: ['@App\Observer\XpEarnedObserver']
 After you instantiate GameApplication, call the subscribe() method on it and pass, as an argument, the @app\Observer\XpEarnedObserver service.
9.2 Kernel.php
 protected function build(ContainerBuilder $container) {
 $container
  ->registerForAutoconfiguration(GameObserverInterface::class)
  ->addTag('game.observer');
 }
 This says that any service that implements GameObserverInterface should automatically be given this game.observer tag... assuming that service has autoconfigure enabled, which all of our services do.
9.3 write a little more code that automatically calls the subscribe method on GameApplication for every service with that tag.
10.1 publish-subscribe pattern variant of observer pattern
10.2 registering listeners
11.3 As easy as this inline listener is, it's more common to create a separate class for your listener. You can either create a listener class, which is basically a class that has this code here as a public function, or you can create a class called a subscriber. Both are completely valid ways to use the pub/sub pattern. The only difference is how you register a listener versus a subscriber, which is pretty minor, and you'll see that in a minute. Let's refactor to a subscriber because they're easier to set up in Symfony.
11.4 But... seriously, why is it printing twice? This is, once again, thanks to auto-configuration! Whenever you create a class that implements EventSubscriberInterface, Symfony's container is already taking that and registering it on the EventDispatcher. In other words, Symfony, internally, is already calling this line right here. So, we can delete it!
12.1 decorator pattern
 Here's the goal: I want to print something onto the screen whenever a player levels up. The logic for leveling up lives inside of XpCalculator

 But instead of changing the code in this class, we're going to apply the decorator pattern, which will allow us to run code before or after this logic... without actually changing the code inside.
 For the decorator pattern to work, there's just one rule: the class that we want to decorate (meaning the class we want to extend or modify - XpCalculator in our case) needs to implement an interface.
 12.2 create decorator class
 13.1 to set up the decoration, I'm instantiating the objects manually, which is not very realistic in a Symfony app. What we really want is for XpEarnedObserver to autowire XpCalculatorInterface like normal, without us needing to do any of this manual instantiation. But we need the container to pass it our OutputtingXpCalculator decorator service, not the original XpCalculator. How can we accomplish that? How can we tell the container that whenever someone type-hints XpCalculatorInterface, it should pass our decorator service?
 13.2 We want OutputtingXpCalculator to be used everywhere in the system that autowires XpCalculatorInterface... except for itself.
 14.1 imagine that there's a core Symfony service and we need to extend its behavior with our own. How could we do that? Well, we could subclass the core service... and reconfigure things so that Symfony's container uses our class instead of the core one. That might work... but this is where decoration shines.

 So, as a challenge, let's extend the behavior of Symfony's core EventDispatcher service so that whenever an event is dispatched, we dump a debugging message.
 14.2 AsDecorator: Making Symfony use OUR Service
 14.3 14.4 Using AsDecorator with OutputtingXpCalculator
 ./bin/console debug:container XpCalculatorInterface --show-arguments
