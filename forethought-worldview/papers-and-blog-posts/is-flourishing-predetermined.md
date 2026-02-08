---
title: "Is Flourishing Predetermined?"
url: https://www.forethought.org/research/is-flourishing-predetermined
---

# Is Flourishing Predetermined?

[Fin Moorhouse](https://www.forethought.org/people/fin-moorhouse) [Carlo Leonardo Attubato](https://www.forethought.org/people/carlo-leonardo-attubato)

## Authors

21st January 2026


### Abstract[1](https://www.forethought.org/research/is-flourishing-predetermined\#user-content-fn-1)

The [Better Futures series](https://www.forethought.org/research/better-futures) argues that the chance humanity achieves a great future is low, but still achievable.[3](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-2) That makes work on flourishing[4](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-3) look especially valuable: there is plenty of room to make great futures more likely, but the default odds aren’t so vanishingly low that they’d be intractable to change. But our overall credence in flourishing might reflect some credence that flourishing is almost impossible, plus some credence that it’s very easy. If so, flourishing would be overdetermined, and hence less tractable[2](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-1.5) to work on than we thought.

We consider how to formalise this argument, and ultimately conclude it’s a real but modest downwards update to the value of working on flourishing. Still, we hope the resulting model is a useful way to make sense of radical uncertainty about the difficulty of other problems.

### Introduction

Imagine you're campaigning for a new bill to pass in your city council. Here are two scenarios:

1. You've assessed that council members are genuinely undecided, and likely to vote based on their own judgment and constituent feedback. You place about even odds on the bill passing.

2. You suspect the mayor has already formed a strong public position for or against the bill, and most council members will follow the mayor's lead. You don't know which way the mayor’s mind is made up, so you place about even odds on the policy passing.[5](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-4)


Although you place even odds on the bill passing in both scenarios, scenario (1) seems more ‘truly’ chancy. In (1), you might expect the outcome of the vote to be more sensitive to a wide range of small changes up to the vote. In (2), the outcome seems more predetermined one way or the other, so influencing just one or a few factors is less likely to influence the overall outcome.

Perhaps for that reason, efforts to lobby council members seem more effective in scenario (1) than (2). Whether you’re in a scenario more like (1) or (2) could matter for whether you choose to spend your money on this campaigning initiative.

With that in mind, here’s another thought experiment. Take millions of planetary civilisations similar to our own, and watch how they play out. Some fraction encounters a near-term catastrophe, the remaining fraction doesn’t. Of the remainder, some fraction flourishes, where we define “flourishing” as achieving a [mostly-great](https://www.forethought.org/research/no-easy-eutopia#1-introduction) [6](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-5) future, conditional on survival.

Like before, you can form a probability distribution over the fraction of surviving civilisations that flourish. In the more intuitively ‘middling’ case, the true proportion of flourishing civilisations could take a range of intermediate values — perhaps because most civilisations could go either way, depending on fairly contingent factors. But in the ‘polarised’ case, most probability mass could be bunched up close to the extremes of zero and one — reflecting a guess that civilisational flourishing is either effectively doomed, or effectively guaranteed, but you’re unsure which.

![Middling vs Polarised](https://images.ctfassets.net/4owxfjx3z3if/1zfFjlJDGudH8LwLLyjKy5/35cf6c5d261121587bcd06c3ac4a8dac/beta_distributions.png?w=3840&q=75&fm=webp)

Comparing 'middling' and 'polarised' probability distributions.


Now, the expected fraction of surviving civilisations that flourish (the mean of your subjective distribution) can be identical in both cases. But in the ‘polarised’ case, like scenario (2) above, flourishing intuitively seems more overdetermined one way or the other, and hence harder to influence.

[Previous Forethought writing](https://www.forethought.org/research/better-futures) argued for the value of work to increase the chance of flourishing being comparable to the value of work to increase the chance of survival. But plausibly, we should have a very polarised probability distribution over a more ‘objective’ (or well-informed) likelihood of our own civilisation flourishing, conditional on survival. And that could imply that work on flourishing is much less tractable than you’d otherwise think. If the distribution is _extremely_ polarised, that could make flourishing look significantly less promising to work on.

### How likely is flourishing by default?

I’ll spell out the argument in some more detail. Here’s the structure:

First, consider all civilisations at roughly our stage of technological development.[7](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-6) Among those that survive, what fraction eventually flourish?[8](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-7) We can form a prior probability distribution over the true fraction, representing our subjective levels of certainty in different answers.

Next, we can ask what happens when we integrate specific knowledge about our own civilisation, by narrowing the reference class to civilisations indistinguishable to us from our own. Consider running very many copies of human civilisation today, with slightly varied starting conditions. We can ask: among the futures which survive, what fraction flourish?[9](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-8) We can think of the question in terms of an evidential update to the first distribution based on observing we’re in our civilisation in particular.

Next, after narrowing the reference class, we can take the ‘objective’ fraction of worlds which flourish by default as a guide to the tractability of work on flourishing. That’s a contentious move, and I’ll discuss why it could be valid.

#### What fraction of human-level civilisations flourish?

Starting with the first question: among those civilisations at roughly our stage of technological development that survive, what fraction flourish by default? My own reaction is to feel wildly uncertain.[10](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-9) I don’t have strong intuitions either way, and I’m similarly uncertain about important sub-questions:

- What, even roughly speaking, does this reference class of civilisations look like?

- Will flourishing turn out to be surprisingly achievable in technological terms?

- Are there self-reinforcing dynamics which point towards flourishing?

- Are there self-reinforcing dynamics which point away from flourishing?


And so on.

#### Choosing an uninformative prior

When forming a prior over an objective probability that we’re very uncertain about, it makes sense to choose an uninformative prior.

Intuitively, an uninformative prior should spread our credence over order of magnitude in odds more evenly than a uniform prior over probability. After all, the sense of radical uncertainty here tracks the sense that roughly 10,000:1 odds aren’t much more or less plausible-seeming than even odds, or roughly 1:10,000 odds. But a uniform distribution in probability implies that an “objective” probability lower than 10,000:1 odds is about a thousand times less subjectively plausible than a probability between 45–55%, which seems overly confident.

Many candidates for an uninformative prior fit that intuition. One especially natural choice[11](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-10) is the [Jeffreys prior](https://en.wikipedia.org/wiki/Jeffreys_prior), which in the binomial case is the [beta distribution](https://en.wikipedia.org/wiki/Beta_distribution) with α=β=0.5\\alpha=\\beta=0.5α=β=0.5. Let’s take that as our starting point.

![Jeffreys](https://images.ctfassets.net/4owxfjx3z3if/5qtqLvxnqQMO55SZ13sRgp/3afb77447e6e5564e8613d0e1e805343/jeffreys_distribution.png?w=3840&q=75&fm=webp)

The Jeffreys prior.


#### Models of flourishing

Now we can get a notch more concrete by considering possible models of flourishing in particular, and seeing what kinds of distribution they generate.

##### The multiplicative model

One model, suggested in [previous writing](https://www.forethought.org/research/no-easy-eutopia), is that the value of the future could be well-described as a product of many independent factors. This would suggest the value of a given civilisation has a heavy-tailed distribution, with very few worlds passing the _mostly-great_ bar.[12](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-11)

Now, suppose instead that we’re uniformly uncertain about the threshold in value required to count as mostly-great, and we imagine that civilisations are distributed in value in some heavy-tailed way, such as you’d get if value were the product of many independent factors. Then the resulting distribution over the fraction of civilisations which exceed the threshold will also be heavy-tailed.

That said, a truly heavy-tailed distribution in value, plus uniform uncertainty about the value threshold, wouldn’t give a U-shaped distribution in the fraction of mostly-great futures: this distribution is especially confident that the fraction is low, and vanishingly confident that it approaches 1.[13](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-12)

Alternatively, we might be uncertain about the degree of correlation between the factors which multiply to give a measure of value, for each civilisation. If the factors are correlated enough, then most civilisations flourish. That would add much more mass to the flourishing fraction being middling or high. But the result still wouldn’t be U-shaped — that would require, for example, placing some credence on the threshold being passed however the factors shake out.[14](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-13)

##### The basin model

On the other hand, we might imagine there are self-reinforcing dynamics which point upwards towards mostly-great futures, such that once a given civilisation passes some threshold, it has a substantial chance of achieving a mostly-great future.

On the simplest model, we might imagine that civilisations initially enter a ‘basin of attraction’ towards flourishing with some likelihood, where flourishing is guaranteed if they enter the basin of attraction, and impossible otherwise. If we were very confident about the likelihood of a random civilisation entering the basin of attraction, our subjective prior distribution over the fraction of flourishing civilisations would then cluster around that likelihood.

Sufficiently good epistemic practices could be one example of a self-reinforcing, bootstrapping dynamic. Starting with some reasonably effective methods for refining beliefs and theories, better beliefs and theories might help spot flawed methods and assumptions, and so on, like the cumulative and error-correcting methods of the empirical sciences. Other starting combinations of beliefs and values might have [“self-sealing” dynamics](https://onlinelibrary.wiley.com/doi/10.1111/theo.12554). Similarly, starting with functional-enough institutions for cooperation could promote trust just enough to improve those institutions in turn, in an upwards spiral.[16](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-14.5)

However, if there is such a basin of attraction (or if there are many), we’re ignorant about the likelihood of entering it. So it could be be more appropriate to spread our credences evenly across the order-of-magnitude size ratio between the region of the space of surviving civilisations representing a basin of attraction, with the rest of the space, between two reasonable-seeming bounds. This is a uniform distribution in log-odds between bounds.[15](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-14)

![Uniform in log odds](https://images.ctfassets.net/4owxfjx3z3if/EBpdOSkB7MCxcYW9DV7Rr/37a44d9f16c0c43af78e93ffb30bfa92/log_odds_uniform_-2.0_2.0.png?w=3840&q=75&fm=webp)

A uniform distribution in log-odds between a lower and an upper bound. Shown in log-odds space (left) and probability space (right).


Finally, there’s no special reason to think our subjective distribution should be symmetrical. On the one hand, remember that we’re _conditioning_ on civilisations which survive, which I expect should mean shifting more weight onto higher fractions, mainly because survival suggests more civilisational competence, and I’d expect civilisational competence to also make flourishing more likely. In other words, I expect flourishing and survival to be correlated. On the other hand, there are some [reasons](https://www.forethought.org/research/no-easy-eutopia) to think that flourishing is simply [very difficult](https://www.forethought.org/research/convergence-and-compromise), in the sense that our expected fraction of surviving civilisations which flourish (the mean of the distribution) is very low. The second factor weighs much more strongly for me.

All things considered, then, my best guess at a reasonable distribution places most weight very close to zero, with reasonable credence spread across middling and high fractions, and perhaps a small bump around higher probabilities.[17](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-15)

![Beta 3](https://images.ctfassets.net/4owxfjx3z3if/7fVFGuGfBl0FVaf6HHrm9p/d6b56b2da28c4435b14878bf880166b9/beta_comparison_a0.01_b0.3.png?w=3840&q=75&fm=webp)

A subjective distribution over the fraction of human-level civilisations which flourish (linear and log scale).


The models which generate big spikes at both ends of the distribution are qualitatively plausible, but I found the parameter values which generate clearly U-shaped distributions are somewhat contrived in practice. In practice, my best-guess parameter values put far less probability mass in p>0.95p>0.95p>0.95 than p<0.05p<0.05p<0.05, and the ‘middling mass’ outweighs p>0.95p>0.95p>0.95.[18](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-16)

#### Will our own civilisation flourish?

Let’s turn to the second question: consider running many copies of human civilisation today, and varying the starting conditions in a bunch of virtually unnoticeable ways. Among those futures which survive, what fraction flourish?

In a sense, this is the same question as above, but the domain of civilisations has been narrowed all the way to just those civilisations indistinguishable from our own. It’s as if you began by knowing you are in some randomly chosen civilisation, and now you learn the civilisation you’re in looks like _this_, and you’re wondering how to update your distribution.

Firstly, you have lost differences between civilisations as a major source of variance between outcomes. You could think that the fate of any indistinguishable set of civilisations is largely fixed by the time they reach human-level technology, but there is a close-to-even mix of civilisations guaranteed to flourish and doomed not to. Then, on narrowing the set of civilisations to be indistinguishable, you know the fraction of _these_ civilisations which flourish will be much closer to the extremes. To the extent you learn anything about which kind of civilisation you’re in, your distribution becomes more polarised.

But the new distribution doesn’t have to be more extreme: the fraction of civilisations like ours which flourish could more plausibly be a more middling number than the fraction of all human-level civilisations.[19](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-17)

And indeed, human civilisation seems more chancy and middling than it could have been, whether that makes us unlucky or lucky compared to other civilisations. In other words, it really looks like flourishing[20](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-18) hangs in the balance, in a way which could easily not be true.

I’ll spell that out. On the one hand, our world isn’t on any obvious course for narrowly prescriptive global government, or total breakdown of good will and cooperative intent, or draconian measures limiting scope for exploration and ambition. On the other hand, ours is not a civilisation that “has its act together” in any conspicuous sense; whereas we could have found ourselves in a reflective, cautious, cooperative, and open-minded civilisation. Since we can imagine finding ourselves in a more predetermined situation, that suggests the share of civilisations just like ours which flourish is more _middling_ than the share of all civilisations which flourish.[21](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-19)

So, we have two considerations to balance: one suggests the updated distribution should be more polarised, and the other suggests it should be more middling. My best guess is that it should be somewhat more middling.[22](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-20) Here’s a sketch,[23](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-21) where the original (all civilisations) distribution is also shown in grey.

![Comparing beta distributions](https://images.ctfassets.net/4owxfjx3z3if/4yDh50YyWo6OsxLLrTQoYp/bb3e5bb74815fef84122f38c070d6b07/beta_overlay_comparison_a0.01b0.3_a0.1b0.6.png?w=3840&q=75&fm=webp)

A subjective distribution over the fraction of near-identical civilisations to ours which flourish (orange), compared with the same distribution for all human-level civilisations (grey). Both linear and log are shown.


The ‘objective’ chance of our own civilisation flourishing — understood as the true fraction of surviving futures like ours which flourish — still looks most likely to be close to zero, with perhaps 10% credence on a ‘middling’ probability within 0.1<p<0.950.1 < p < 0.950.1<p<0.95, and a small second mode approaching p=1p=1p=1.

### From probability to tractability

Remember that the motivating intuition for this line of thinking is that, if the ‘objective’ likelihood of flourishing is very low or very high, then we should also expect pro-flourishing efforts to be less tractable than if the objective chance[24](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-22) were some middling fraction. So a polarised distribution suggests lower tractability than a middling one. In the intro, I gave the example of an upcoming election, which fits the intuition.

Next, I’ll describe a model which we can apply to flourishing.

#### The hidden-difficulty model

First, we assume the objective chance ppp of some desired outcome is the logistic function of the sum of a 'baseline' parameter, plus a ‘control’ parameter α\\alphaα representing the shift we make through deliberate effort. In other words: with a unit of effort, you move the log-odds of the outcome by the same amount, whatever the starting probability.

![Logistic function](https://images.ctfassets.net/4owxfjx3z3if/3bBJv5aOVTAzET6Wv7kmLd/53f4fcfc70e9d6c637c97648cf72186b/marginal_effort.png?w=3840&q=75&fm=webp)

Converting between log-odds and probability. The same shift in log-odds results in a larger shift in probability when the initial probability is more middling.


Here, the baseline parameter represents the default log-odds of the outcome, and the ‘control’ parameter represents how moveable the log-odds are with a unit of effort.

The marginal gain in probability from a unit of effort, from now on _movability_,[25](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-23) is then: α⋅p(1−p)\\alpha \\cdot p(1-p)α⋅p(1−p).[26](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-23.1) Hence, the same unit of effort moves the needle least when ppp is close to 0 and 1, and the most when p=0.5p=0.5p=0.5.

Furthermore, we're uncertain about the objective chance of the desired outcome, so we’re also uncertain about the movability of the objective chance with a unit of effort.

We can however consider the _expected_ movability, given your subjective distribution over the objective chance of the outcome. Where pˉ\\bar{p}pˉ​ is the expected value of ppp, and factoring for the control parameter α\\alphaα, it turns out that the expected movability is equal to pˉ(1−pˉ)\\bar{p}(1-\\bar{p})pˉ​(1−pˉ​) minus the _variance_ of your distribution.[27](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-23.2)

It follows that, fixing pˉ\\bar{p}pˉ​, adding uncertainty about the true likelihood ppp always decreases the expected movability of making the desired outcome more likely. The expected movability can get arbitrarily close to zero as the distribution gets more polarised, but it’s never higher than when you’re certain of ppp.

#### Limitations of the hidden-difficulty model

In this model, even a very low subjective probability doesn't imply low movability,[28](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-23.5) because your level of influence over log-odds (α\\alphaα) can be high.[29](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-24) Similarly, a middling subjective probability doesn't imply high movability, because your level of influence over log-odds could be low,[30](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-25) _or_ your distribution over the objective probability could be very polarised. So to shed any insight on the expected movability of a problem, you need a way of estimating or controlling for the control parameter (α\\alphaα).

If we try to describe various real problems in terms of this model, we'll notice that the control parameter (α\\alphaα) varies enormously between problems. Moreover, it can also vary _within_ a problem — additional units of effort might shift the log odds by very different amounts.[31](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-26)

So the hidden-difficulty model is most useful when there is some notion of an ‘objective’ probability of the desired outcome, and when a unit of effort will move the log-odds by roughly the same amount whatever the objective probability, and when this unit of effort can be pinned down.[32](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-27) Then the model can tell us useful things about how the expected movability of making the outcome more likely depends on the shape of our uncertainty over its actual default likelihood.

Otherwise, I’m definitely not saying that this model is widely applicable, in the way that (for example) the ‘ITN’ framework is designed to be.

However, the model does seem decently well-suited to flourishing. In fact, even if we’re also very uncertain about the level of control we’re likely to have over flourishing in log odds, we can do two things: we can figure out the downward update to movability after switching from a naively certain to uncertain distribution over the objective probability of flourishing; and we can _compare_ flourishing with survival by assuming the level of control to be similar. I work through both cases next.

### Applying the model to flourishing

Imagine we began by naively thinking we can be confident of the objective chance of flourishing by default. Then, we integrate the point above: that we should expect the true movability to be lower than we thought.

The downward penalty after updating to represent our (very wide) uncertainty about the objective chance of flourishing is just the variance of the new distribution, as a proportion of pˉ(1−pˉ)\\bar{p}(1-\\bar{p})pˉ​(1−pˉ​).[33](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-28)

Using my best guess distribution of the objective chance of flourishing, I find that updating to a widely uncertain distribution over the objective probability reduces the expected movability by about 60%.[34](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-29)

#### Comparing flourishing with survival

In the previous section, I suggested a model for the intuition that radical uncertainty about the default likelihood of a desired outcome reduces its expected movability, given some assumptions. Since we’re radically uncertain about the likelihood of flourishing, we should be correspondingly more pessimistic about the movability of that default likelihood.

In the [framework I’m using](https://www.forethought.org/research/better-futures), flourishing is contrasted with survival. Recall that survival means not entering a state much closer in expected value to extinction than to a mostly great future, and which seems to most people today like a disaster which destroys almost all potential value. Flourishing means reaching a [mostly-great](https://www.forethought.org/research/no-easy-eutopia#1-introduction) future, conditional on survival.

The same argument above also applies to survival. Parallel with flourishing, we can imagine slightly varying our present civilisation in many indistinguishable ways, and then ask what fraction of those civilisations would ultimately survive, whether or not they go on to flourish.

Just like before, our distribution over the true fraction is going to be spread out. It’s not crazy to imagine that survival is really close to guaranteed: the risks we know about are terrible but recoverable, and the potentially irrecoverable risks like overt forms of AI takeover turn out to be easy to avert, or not likely in the first place, like the [millennium bug](https://en.wikipedia.org/wiki/Year_2000_problem). On the other hand, it’s hard to entirely rule out the most pessimistic stories about the coming century — for example, that if anyone builds superintelligent AI, we’re doomed. A 50% subjective credence in survival doesn’t imply certainty that if we keep re-running the tape, we survive 50% of the time. Things could be (and likely are) more pre-ordained either way.

So, the same downward update to expected movability applies; but it could be a much larger or smaller update compared to the flourishing example. In fact, I guess it’s a smaller update. The reason is that we know more about the chance of survival, whereas the chance of flourishing depends more on theoretical, philosophical, and downright unknown considerations which we should be widely uncertain about.[35](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-30)

By contrast, we arguably have more observational evidence about the chance of survival. We’ve plausibly seen situations — like [nuclear near-miss incidents](https://en.wikipedia.org/wiki/Nuclear_close_calls) — where humanity already came quite close to global catastrophe. That seems like evidence that solidly rules out worlds where survival is _totally_ guaranteed.[36](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-30.5) On the other hand, it’s not very difficult to tell a story where humanity survives. Because we know we can survive at current technology levels, one story would be that technological progress broadly stalls out at current levels, only cautiously progressing along the specific directions needed to keep flourishing alive as a possibility.

For the sake of concreteness, here’s a reasonable-seeming subjective distribution for survival:[37](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-31)

![Beta 2](https://images.ctfassets.net/4owxfjx3z3if/oYWLejyUIBoJnmpbsnLWS/735cff620a612983ccec7c7e58a7add9/beta_linear_a3.0_b1.2.png?w=3840&q=75&fm=webp)

A subjective probability distribution over the fraction of near-identical civilisations which survive.


This distribution has a lower variance to my distribution for flourishing, and a less extreme mean. So if we imagine that the naive expected movabilities of flourishing and survival are initially equal, then we should conclude that the penalty to the expected movability of flourishing is bigger than the penalty to survival.[38](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-32) In other words, after making this update, working on survival looks about twice as promising in relation to movability than it did before.

#### Prioritising between survival and flourishing

The value of making flourishing more likely depends on the chance of survival, and vice-versa. To simplify things, we can approximate the value of the future just as the chance of surviving and then flourishing: the product of the two likelihoods.[39](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-33)

The marginal value of increasing the chance of flourishing with a unit of effort, then, is just the movability of flourishing multiplied by the chance of survival; and vice-versa for the marginal value of making survival more likely.

If we assume survival and flourishing are equally tractable, then it follows that the _less likely_ factor is more relatively valuable to work on, and increasing the chance of one factor is more absolutely valuable in worlds where the default chance of the other factor is higher.

Instead we can assume an equal level of control over log-odds,[40](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-34) but not movability. Then, expected movability is lower when the expected likelihood of the outcome being considered is more extreme.[41](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-35)

We can combine these points to understand how, all else equal, the value of working on flourishing and survival depends on their default likelihoods.[42](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-36)

![Comparing marginal importance](https://images.ctfassets.net/4owxfjx3z3if/4lnJHwjKkddeWkMPoIgLLq/4b24ca19499ee8ce466c766f1225dc78/marginal_importance_pair.png?w=3840&q=75&fm=webp)

The marginal value of applying the same unit of effort to increasing the chance of flourishing and survival, given different background probabilities.


We can also look at the _absolute_ difference in value between working on flourishing and survival, in terms of the value of a mostly-great future. The graph below shows this.

![Which factor to prioritise](https://images.ctfassets.net/4owxfjx3z3if/6o67P81sZpTulVazA2JzHh/c030227b4b0f9fe3b546e27b017da100/marginal_importance_priority.png?w=3840&q=75&fm=webp)

The absolute difference in the marginal value of applying the same unit of effort to increasing the chance of flourishing versus survival, given different background probabilities.


Movability is higher towards middling probabilities, and the stakes are higher when the other factor is more likely. Thus, working on flourishing looks more absolutely valuable when the chance of flourishing is middling, and the chance of survival is high. Working on survival looks more absolutely valuable when the chance of survival is middling, and the chance of flourishing is high. When both factors are low, the stakes of the future seem overall lower, and vice-versa. Moreover, the _relative_ importance of working on flourishing is always higher when the chance of survival is higher, and vice-versa.[43](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-37)

#### Interactions between survival and flourishing

The effects of different interventions on one factor might in practice depend differently on the default likelihood of the _other_ factor. For example, perhaps some way to make flourishing more likely has a bigger effect on the chance of flourishing in worlds where the chance of survival is low. In such cases, the best interventions might not always be the interventions with the highest expected movability.

Here’s an example: suppose you’re choosing between interventions to increase the likelihood of survival, where some “pessimistic” interventions improve the chance of survival the most in worlds where the chance of flourishing is low, and other “optimistic” interventions work best when the chance of flourishing is high. Even if the optimistic interventions have less expected effect on the chance of survival, they might nonetheless be more valuable in expectation, because they pay off in worlds where it’s more valuable for them to pay off.

The rule of thumb is to act like you’re optimistic about the default chance of the other factor panning out. And that’s a more generally applicable heuristic, since the reasons don’t especially depend on the hidden-difficulty model.

## Conclusion

Often, it’s hugely uncertain how likely a problem is to be solved by default: the ‘true’ likelihood may be very likely close to either impossibility or inevitability. In those cases, working on the problem looks less valuable than it would otherwise.

At first glance, this line of thought presents a reason to be more pessimistic than before about the value of working on flourishing, as compared with other potential global priorities. I suggested one reasonable model of the intuition, and on my own numbers, I found that it should indeed make us less than half as optimistic about the value of flourishing before integrating this lesson.

Since the update to survival seems much smaller, I find that survival comes out looking about twice as good relative to flourishing, compared to how they looked before. Interactions between the two factors could matter, too: when intervening on one factor, we should often plan “optimistically” with respect to the other.

Reflecting on this whole framework, and the model of tractability, I feel pretty cautious about drawing any big conclusions. ‘Flourishing’ and ‘survival’ themselves are extremely abstract categories, and it’s possible that these abstractions wouldn’t hold up well if we really understood the situation. Contemplating the value of abstract categories (like “existential risk reduction”) can be a useful orienting device. I think we should learn from the most robust and relevant abstract considerations, but then pay most attention to the case-by-case promisingness of concrete interventions. The rest, when we’re so uncertain, is noise.

Frankly, I'm also struck by how small the penalties to expected movability seem to be on this model, even for probability distributions which appear almost totally predetermined on their face. When we’re so wildly uncertain, even a 2x update in either direction feels like small change.

Still, I think something like the hidden-difficulty model is often relevant when prioritising between problems, and deserves to be part of the toolkit for problem prioritisation.

I’ll highlight two rules of thumb which I think apply, at least in an “all else equal” sense, more widely. First, when you’re trying to bring about an outcome of very uncertain default likelihood, consider that the outcome is basically overdetermined already. Thus, prioritise problems whose difficulty you know, or on bringing about outcomes whose default chance you know. Second, when acting on one of several factors that multiply, act optimistically about the other factors.

## Acknowledgements

Owen Cotton-Barratt wrote the original note explaining the central ideas. Carlo Leonardo Attubato suggested the formal model, and is thus credited as a co-author. Fin wrote the text.

Major credit also goes to the roughly 40 people who elaborated on Owen’s ideas as part of a work trial for Forethought. If you are one of those people and would like to be credited by name, please let me (Fin) know.

Thanks also to Max Dalton, Rose Hadshar, Mia Taylor, and Stefan Torges for comments. Errors are mine.

## Appendix — the hidden-difficulty model

This section walks through the “hidden-difficulty” model in more detail. The model is originally due to Carlo Leonardo Attubato.[44](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fn-38)

Recall that the objective chance of some outcome OOO is represented as the logistic function of the sum of a 'baseline' parameter BBB, plus a parameter α\\alphaα representing the shift made through deliberate effort EEE on top of the expected level of effort:

P(O)=logit⁡−1(B+αE)=11+e−(B+αE)P(O)=\\operatorname{logit}^{-1}(B+\\alpha E) = \\frac{1}{1 + e^{-(B + \\alpha E)}}P(O)=logit−1(B+αE)=1+e−(B+αE)1​

The marginal gain in probability from a unit of effort, is then:

ddEP(O)=α⋅p(1−p),where p≡P(O)\\frac{d}{dE}P(O)=\\alpha \\cdot p(1-p), \\quad \\text{where } p \\equiv P(O)dEd​P(O)=α⋅p(1−p),where p≡P(O)

### Movability

We use “movability” to refer to the quantity ddEP(O) \\frac{d}{dE}P(O)dEd​P(O). We take an uncertain subjective distribution over the value of ppp, assuming α\\alphaα is independent of ppp. Then:

E\[ddEP(O)\]=E\[α⋅p(1−p)\]=α⋅E\[p(1−p)\]\\mathbb{E}\\left\[\\frac{d}{dE}P(O)\\right\] = \\mathbb{E}\\left\[\\alpha\\cdot p(1-p)\\right\] = \\alpha\\cdot\\mathbb{E}\\left\[p(1-p)\\right\]E\[dEd​P(O)\]=E\[α⋅p(1−p)\]=α⋅E\[p(1−p)\]

Note:

E\[p(1−p)\]=E\[p\]−E\[p2\]=E\[p\]−(Var(p)+E\[p\]2)=E\[p\](1−E\[p\])−Var(p).\\mathbb{E}\\left\[p(1-p)\\right\]=\\mathbb{E}\\left\[p\\right\]-\\mathbb{E}\\left\[p^2\\right\] = \\mathbb{E}\\left\[p\\right\]-(\\text{Var}(p)+\\mathbb{E}\\left\[p\\right\]^2)=\\mathbb{E}\\left\[p\\right\](1-\\mathbb{E}\\left\[p\\right\])-\\text{Var}(p).E\[p(1−p)\]=E\[p\]−E\[p2\]=E\[p\]−(Var(p)+E\[p\]2)=E\[p\](1−E\[p\])−Var(p).

Thus, the expected movability is equal to pˉ(1−pˉ)\\bar{p}(1-\\bar{p})pˉ​(1−pˉ​) minus Var(p)\\text{Var}(p)Var(p), all multiplied by the control parameter α\\alphaα.

Given this, we can place bounds on expected movability:

0≤α⋅E\[p(1−p)\]≤α⋅E\[p\](1−E\[p\])0\\leq\\alpha\\cdot\\mathbb{E}\\left\[p(1-p)\\right\]\\leq\\alpha\\cdot\\mathbb{E}\[p\](1-\\mathbb{E}\[p\])0≤α⋅E\[p(1−p)\]≤α⋅E\[p\](1−E\[p\])

Demonstrating the lower bound:

Since 0≤p≤10 \\leq p \\leq 10≤p≤1, we have p2≤pp^2 \\leq pp2≤p for all ppp, so E\[p2\]≤pˉ\\mathbb{E}\[p^2\] \\leq \\bar{p}E\[p2\]≤pˉ​.

By definition, Var(p)=E\[p2\]−pˉ2≤pˉ−pˉ2=pˉ(1−pˉ)\\text{Var}(p) = \\mathbb{E}\[p^2\] - \\bar{p}^2 \\leq \\bar{p} - \\bar{p}^2 = \\bar{p}(1-\\bar{p})Var(p)=E\[p2\]−pˉ​2≤pˉ​−pˉ​2=pˉ​(1−pˉ​).

Since E\[p(1−p)\]=pˉ(1−pˉ)−Var(p)\\mathbb{E}\[p(1-p)\] = \\bar{p}(1-\\bar{p}) - \\text{Var}(p)E\[p(1−p)\]=pˉ​(1−pˉ​)−Var(p) and α≥0\\alpha \\geq 0α≥0 by stipulation, it follows that α⋅E\[p(1−p)\]≥0\\alpha \\cdot \\mathbb{E}\[p(1-p)\] \\geq 0α⋅E\[p(1−p)\]≥0. □\\square□

The upper bound is easier to see, since Var(p)\\text{Var}(p)Var(p) is never negative.

The expected movability approaches zero when Var(p)=E\[p\](1−E\[p\])\\text{Var}(p) = \\mathbb{E}\[p\](1-\\mathbb{E}\[p\])Var(p)=E\[p\](1−E\[p\]), which you get when you're sure ppp is either 0 or 1; that is, OOO is perfectly predetermined either way (a Bernoulli random variable).

On the other hand, expected movability approaches its maximum firstly when Var(p)=0\\text{Var}(p) = 0Var(p)=0 (you’re certain of the objective probability), and secondly when pˉ=0.5\\bar{p}=0.5pˉ​=0.5 (p(1−p)p(1-p)p(1−p) is maximised at p=0.5p=0.5p=0.5 by symmetry).

One upshot of the model is that when your expected value of ppp is very close to 0 or 1, then the movability of changing it must be low (holding α\\alphaα fixed). On the other hand, when your expected value of ppp is middling, the expected movability could be low or high, and it's more important to consider the shape of your distribution over ppp (and its variance in particular).

### Assumptions

The formula above for expected movability assumes that the control parameter α\\alphaα is independent of the objective probability ppp. To see why, suppose α\\alphaα depends on ppp, written as α(p)\\alpha(p)α(p). Then:

E\[ddEP(O)\]=E\[α(p)⋅p(1−p)\]\\mathbb{E}\[\\frac{d}{dE}P(O)\] = \\mathbb{E}\[\\alpha(p) \\cdot p(1-p)\]E\[dEd​P(O)\]=E\[α(p)⋅p(1−p)\]

Unlike the independent case, we cannot factor α\\alphaα out of the expectation. Instead:

E\[α(p)⋅p(1−p)\]=∫α(p)⋅p(1−p)⋅fp(p)dp\\mathbb{E}\[\\alpha(p) \\cdot p(1-p)\] = \\int \\alpha(p) \\cdot p(1-p) \\cdot f\_p(p)\\: dpE\[α(p)⋅p(1−p)\]=∫α(p)⋅p(1−p)⋅fp​(p)dp

Where fp(p)f\_p(p)fp​(p) is the probability density over ppp. This integral does _not_ generally simplify to α⋅\[pˉ(1−pˉ)−Var(p)\]\\alpha \\cdot \[\\bar{p}(1-\\bar{p}) - \\text{Var}(p)\]α⋅\[pˉ​(1−pˉ​)−Var(p)\].

### Comparing survival and flourishing

Here’s a table to compare the value of survival and flourishing, assuming an equal control parameter α\\alphaα.

|  | Low default flourishing | Medium default flourishing | High default flourishing |
| --- | --- | --- | --- |
| **Low default survival** | Both unimportant, both less movable. | Flourishing more movable, survival more important. | Both less movable, survival more important. |
| **Medium default survival** | Survival more movable, flourishing more important. | Both important, both movable. | Survival more movable, survival more important. |
| **High default survival** | Both less movable, flourishing more important. | Flourishing more movable, flourishing more important. | Both especially important, both less movable. |

### Movability vs tractability

_Movability_, as I've defined it, roughly tracks the common-sense notion of _tractability_.

But "tractability" happens to have a formal definition as part of the [_importance (or scale), tractability, and neglectedness_ (or ITN) framework](https://forum.effectivealtruism.org/topics/itn-framework). This definition is related but distinct from movability — and that's why I used a new term for movability.

On the ITN framework, tractability is [defined as](https://www.effectivealtruism.org/articles/prospecting-for-gold-owen-cotton-barratt) the elasticity of progress on a problem with respect to effort on the problem:

%dS%dE\\frac{\\% dS}{\\%dE}%dE%dS​

%dS\\%dS%dS is the percentage of the entire remaining problem SSS being solved (by a unit of effort), which in our case is 1−p1-p1−p. %dE\\%dE%dE is that unit of effort as a percentage of total effort, EEE.

In this context, the "problem" being solved is the remaining objective chance 1−p1-p1−p that we don't reach the desired outcome (survival or flourishing in our case, the outcome with probability ppp). So when we increase ppp on the margin by dpdpdp, the percentage of the remaining problem being solved is dp(1−p)−1dp(1-p)^{-1}dp(1−p)−1. The percentage increase in total effort is dE/EdE/EdE/E. Thus:

Tractability=dp/(1−p)dE/E\\text{Tractability} = \\frac{dp/(1-p)}{dE / E} Tractability=dE/Edp/(1−p)​

Recall:

Movability=ddEP(O)=dpdE\\text{Movability}=\\frac{d}{dE}P(O)=\\frac{dp}{dE}Movability=dEd​P(O)=dEdp​

Note:

dp/(1−p)dE/E=dpdE⋅E1−p\\frac{dp/(1-p)}{dE / E} = \\frac{dp}{dE} \\cdot \\frac{E}{1-p}dE/Edp/(1−p)​=dEdp​⋅1−pE​

Thus:

Tractability=Movability⋅E1−p\\text{Tractability} = \\text{Movability} \\cdot \\frac{E}{1-p}Tractability=Movability⋅1−pE​

And, equivalently:

Movability=Tractability⋅1−pE\\text{Movability} = \\text{Tractability} \\cdot \\frac{1-p}{E}Movability=Tractability⋅E1−p​

Two observations here. First, tractability scales with total effort, whereas movability is defined in terms of absolute marginal effort. This makes sense: when total effort so far is high, yet the objective probability _still_ moves a lot in response to more effort, it must be very tractable. Whereas if your marginal unit of effort follows very little effort so far, the problem may be movable only because it's extremely neglected (in ITN terms). By contrast, movability bakes in neglectedness: a problem of fixed tractability is more movable if it's more neglected.

Second, tractability factors out the absolute magnitude of the difference in probability, only considering what fraction of the remaining gap gets closed. Whether a unit of effort shifts 98% to 99% or 80% to 90%, those problems are equally tractable, but differ in movability by a factor of 10.

In other words, when comparing interventions aimed at making the same outcome more likely, then movability is proportional to ITN. When comparing interventions and outcomes, the product of movability and the full counterfactual value of the outcome VVV is proportional to ITN — since I∝(1−p)⋅VI \\propto (1-p) \\cdot VI∝(1−p)⋅V and N∝1/EN \\propto 1/EN∝1/E:

I×T×N∝(1−p)⋅V⋅T⋅1E=T⋅1−pE⋅V=M⋅VI \\times T \\times N \\propto (1-p) \\cdot V \\cdot T \\cdot \\frac{1}{E} = T \\cdot \\frac{1-p}{E} \\cdot V = M \\cdot VI×T×N∝(1−p)⋅V⋅T⋅E1​=T⋅E1−p​⋅V=M⋅V

1

This piece is based on ideas developed and shared by Owen Cotton-Barratt. Carlo Leonardo Attubato suggested the formal model, and Fin wrote the text (hence the first-person pronouns). [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-1)

2

Here I mean the imprecise, common-sense notion of "tractability"; not the technical conception of tractability from the [“ITN” framework](https://forum.effectivealtruism.org/topics/itn-framework). [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-1.5)

3

Say, a subjective credence of 10%. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-2)

4

Making great futures more likely given survival. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-3)

5

And other potentially relevant facts are otherwise equal, such as the time remaining to the vote. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-4)

6

A mostly-great future is a future which achieves at least _most_ of the potential of a best feasible future, or more precisely (if applicable) at least 50% of its value. A best feasible future is a future humanity would achieve if things went exceptionally well — at the 99.99th percentile of our distribution of how well things could go, according to a well-informed observer. Note that on this definition, the subjective likelihood of flourishing cannot be lower than 0.01%. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-5)

7

Meaning something like: roughly at our place on the [Kardashev scale](https://en.wikipedia.org/wiki/Kardashev_scale), or roughly as likely to survive as defined, and (if relevant) similarly isolated from other more advanced civilisations. I’ll call this _human-level_ for short. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-6)

8

On some metaethical views, the content of “flourishing” depends contingently on one’s own values, where prospects for convergence on those same values are low (for example, specifically privileging human life). On these views, “what fraction eventually flourish” is ambiguous between flourishing according to you, or according to each civilisation. I intend to mean something like the second reading: what fraction flourishes according to their own values. The reason for preferring this interpretation is that the answer is likely more informative about the narrower question of, roughly speaking, how objectively likely our own civilisation is to flourish. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-7)

9

This is similar to asking what odds an ideal observer would assign to human civilisation flourishing, conditional on survival; where the observer has seen very many similar civilisations play out, has granular but not entirely perfect knowledge of our civilisation’s starting conditions, and initially shares your values if you think that’s relevant. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-8)

10

There’s an interesting parallel with the idea of “complex cluelessness” (see [Greaves, 2016](https://philarchive.org/rec/GREC-38)). The case where the objective default probability is known is loosely comparable with “simple cluelessness”. Here, we take the effect of trying to make the outcome more likely as increasing the objective probability. Then there is always a chance that trying to make the outcome more likely makes no difference, or even causes the outcome not to happen. But this isn’t necessarily problematic. This is true but not obviously problematic, per Greaves: we are still confident about our _ex ante_ effect on the probability of the outcome. By contrast, being widely uncertain about the objective probability is more like complex cluelessness, where we’re uncertain about _systematic_ tendencies — in a sense, we’re not confident that our efforts will meaningfully shift the objective probability of the outcome. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-9)

11

The Jeffreys prior is the unique choice of prior which is invariant under reparameterization, meaning that you get the same prior whether you derive it while thinking in terms of probability, odds, log-odds, or any other transformation. In other words, it's the prior to choose when you're unsure which parameterisation to use, but you think that choice shouldn't matter. By contrast, the prior which is literally uniform across log odds (the [Haldane prior](https://en.wikipedia.org/?title=Haldane_prior&redirect=no)) is not a good choice: it’s an [improper prior](https://andrewcharlesjones.github.io/journal/improper-priors.html), and clearly too extreme. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-10)

12

Note that the prior distribution over the true fraction of civilisations which pass the mostly-great bar is entirely different from the value distribution of civilisations. For instance, if you know both the value distribution and the mostly-great threshold, your distribution over the fraction of mostly-great civilisations would just be a point mass. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-11)

13

If the value distribution has PDF f(x)f(x)f(x) on \[0,M\]\[0, M\]\[0,M\] and we're uniformly uncertain about a threshold TTT, the distribution over the fraction exceeding the threshold has PDF inversely proportional to fff evaluated at the corresponding quantile. Intuitively: in sparse regions (the tail), small threshold changes barely move the survival probability, so those near-zero fractions pile up in the resulting PDF of the fraction of flourishing civilisations. There is no mirror image pile-up of at high fractions: probability mass near one only appears if the value distribution is sparse approaching 0. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-12)

14

For a true second mode as the fraction approaches 1, the probability density in the value distribution would need to approach 0 as value approaches 0 (so there is non-negligible probability that virtually every civilisation passes the threshold, for tiny thresholds). For any reasonable way to model a product of factors with uncertain degree of correlation, the probability density diverges as value approaches 0, so this model probably doesn’t give a second mode approaching 1. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-13)

15

Here we assume civilisations are spread out uniformly across this space, and a civilisation flourishes just in case it finds itself inside a basin of attraction. So our distribution over the fraction of civilisations which flourish should be the same as our distribution over the fraction of civilisation-space occupied by a basin of attraction. The ratio between the volume of civilisation-space inside a basin and the volume outside it is exactly the odds that a random civilisation flourishes. If we are agnostic about the scale of this ratio—treating each order-of-magnitude difference in basin size as equally plausible—then our credence is naturally uniform in the log of this ratio, i.e. uniform in log-odds. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-14)

16

For a discussion of self-reinforcing dynamics relating to corruption, see [Andvig, J.C. and Moene, K.O. (1990)](https://www.sciencedirect.com/science/article/abs/pii/016726819090053G). [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-14.5)

17

Beta(0.01, 0.3) [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-15)

18

My guess at a reasonable distribution over the fraction of human-level civilisations which flourish (above) is beta(0.01, 0.3). This is a bimodal distribution, but still about 5 times more probability mass lives in 0.05<p<0.95 than p>0.95. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-16)

19

This is suggested by the [law of total variance](https://en.wikipedia.org/wiki/Law_of_total_variance): Var(Y)=E\[Var(Y∣X)\]+Var(E\[Y∣X\])\\text{Var}(Y) = \\mathbb{E}\[\\text{Var}(Y\|X)\] + \\text{Var}(\\mathbb{E}\[Y\|X\])Var(Y)=E\[Var(Y∣X)\]+Var(E\[Y∣X\]). The second term captures between-group variance in means, which vanishes upon conditioning on a particular group, which is what we’re doing when we condition on a set of identical civilisations. This guarantees that the _expected_ conditional variance of the new distribution, after conditioning on a random indistinguishable set of civilisations, is lower than the variance of the overall distribution (assuming there is any variance between indistinguishable sets of civilisations). A specific subgroup could have higher variance if it happens to be unusually heterogeneous. The claim _always_ holds if conditional variance doesn't depend on which subgroup you're in ( [homoscedasticity](https://en.wikipedia.org/wiki/Homoscedasticity_and_heteroscedasticity)), or more generally whenever the between-group variance in means exceeds any above-average conditional variance in your particular subgroup. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-17)

20

Here and elsewhere, “flourish” is shorthand for “flourish, conditional on survival”. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-18)

21

To clarify, I am assuming that we could help ourselves to information about human civilisation to inform our best-guess distribution over the fraction of all civilisations which flourish. It’s not exactly clear what it would mean to imagine not having this knowledge when forming the prior, but learning about our own civilisation would update the all-civilisation distribution, to the extent our own civilisation is evidence about the nature of other civilisations. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-19)

22

That is, comparing my subjective distribution over the fraction of human civilisations which flourish, to my subjective distribution over the fraction of all human-level civilisations which flourish. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-20)

23

Beta(0.1, 0.6) [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-21)

24

Here and elsewhere, I mean for “objective chance (or likelihood, probability, odds)” to be a shorthand for the _fraction_ of worlds in the relevant scenario. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-22)

25

Note this is different from how “tractability” is used in the [“importance (or scale), neglectedness, and tractability”](https://forum.effectivealtruism.org/topics/itn-framework) framework, where tractability is typically understood as the elasticity of progress on a problem with respect to effort on the problem. In this context, I think it’s easier to reason about movability: I find it slightly confusing to track how the same percentage point increase in the chance of the desired outcome can imply higher tractability if the outcome is more likely by default, but compensatingly lower “importance”. Similarly, it’s a confusing consequence of the ITN terminology as applied to this context that, holding everything else fixed, higher total effort so far implies higher tractability (and compensatingly lower neglectedness). I find it easier to think about movability, and the maths is more straightforward. I suggest just treating this model as an alternative to the scale, neglectedness, and tractability framework, but see the Appendix for details on how movability relates to tractability in the ITN sense. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-23)

26

See Appendix for derivation. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-23.1)

27

See Appendix for derivation. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-23.2)

28

That said, observing substantial prior effort could count as some evidence for lower movability. For marginal effort to have high impact, we need the current log-odds to be near zero. But this requires two independent quantities — the true baseline and the accumulated effect of prior effort — to nearly cancel. The larger the existing effort, the more precisely the true baseline must offset it. So observing large existing efforts should make us somewhat pessimistic about movability, by increasing the variance of the objective chance. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-23.5)

29

At the extreme high end of control, take the case of whether your favourite song gets played at a party, when the music is set to shuffle. Without specific effort on your part, the expected objective chance can be very low. But you can ask to play your favourite song, massively increasing the log odds that it gets played. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-24)

30

Take the case of being a single voter in a genuinely chancy election, when you are very confident of the objective likelihood your preferred candidate succeeds. That's because whatever the baseline log odds of success for your preferred candidate, a single vote is unlikely to shift the log odds a great deal. This is an importantly different point from the case where you expect the movability of the outcome is low, because you think that whether your candidate wins is already effectively predetermined one way or the other. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-25)

31

Note why this is a problem even when estimating movability at a single margin: if the control parameter α\\alphaα itself depends on the objective probability ppp (for instance, if it's easier to shift log-odds when ppp is moderate versus extreme), then deriving the expected tractability requires integrating over the joint distribution of α\\alphaα and ppp. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-26)

32

Meaning either you can estimate the absolute value, or you can safely assume it’s the same between problems, in order to make comparisons. Alternatively, it could be ambiguous how to decompose low movability into a low control parameter, or high variance in the prior over objective likelihoods, and also unclear how to estimate either independently. In those cases the model is not going to be useful. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-27)

33

So if the expected probability is 0.2, and the variance of the distribution is 0.08, then the baseline movability would be proportional to 0.2×0.8=0.16, and the downward penalty is 0.08/0.16=50%. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-28)

34

Recall this was a beta distribution with alpha parameter set to 0.1, and beta parameter set to 0.6, with mean 0.14. The variance of this distribution is about 0.07. Factoring out α\\alphaα, the movability of the naive distribution (a point mass at the same expected probability) was 0.12. Subtracting variance, the updated distribution reduces the expected movability to about 0.05. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-29)

35

In this case, I think the biggest reason for a distribution with most mass close to the extremes is based on uncertainty about what it would take for the outcome to happen, and what the future looks like in those terms. If we’re uncertain in this way, we should pick some kind of uninformative prior, and uninformative priors are spread out in log space. Of course, you can always contrive a situation where you exactly know the statistical generating mechanisms at play, and you exactly know the criteria for the outcome being met. But it just turns out that those mechanisms generate a distribution that looks an awful lot like an uninformative prior. In other words, it could be the case that you are not conceptually or theoretically confused about what's going on, so your only uncertainty is empirical. But you just happen to know that the world today is compatible with basically two main outcomes: one in which things are predetermined, the outcome is predetermined to happen; another in which the outcome is predetermined not to happen. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-30)

36

I doubt a full-scale nuclear exchange would have caused human extinction. But nuclear near-miss incidents show that it’s entirely possible for civilisation to come to the brink of deploying the most destructive technology of the time, relying partly on good luck to avoid disaster. If the most destructive technology of the time could one day prevent our survival, which is independently plausible, then this is evidence we may deploy it nonetheless. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-30.5)

37

This is beta(3,1.2). My (Fin’s) own inside view is a bit more optimistic. Arguably, there should be a second peak of probability mass approaching p=0 (it seems easier to describe cases where the objective chance of survival falls in the range \[0.0, 0.1%) than in the range \[42.4%, 42.3%)). But adding this small peak wouldn’t significantly change the bottom-line results. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-31)\
\
38\
\
Recall we are comparing beta(0.1, 0.6) (flourishing) with beta(3, 1.2) (survival). In both cases we are updating from pˉ(1−pˉ)\\bar{p}(1-\\bar{p})pˉ​(1−pˉ​) to pˉ(1−pˉ)−Var(p)\\bar{p}(1-\\bar{p}) - \\text{Var}(p)pˉ​(1−pˉ​)−Var(p). For flourishing, this is roughly a 60% haircut; and for survival this is roughly a 20% haircut. So flourishing ends up looking about half as good as before (comparing the remaining roughly 40% vs roughly 80%). [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-32)\
\
39\
\
See [MacAskill and Trammell (2025)](https://www.forethought.org/research/supplement-the-basic-case-for-better-futures#the-sf-model) on the “SF model”. Note that I’m simplifying things even further. MacAskill and Trammell represent flourishing as EV(future \| survival). I assume that most expected value comes from mostly-great futures, and so I opt to represent flourishing as p(mostly-great future \| survival) × V(mostly-great future), where I take V(mostly-great future) as fixed. Then it’s meaningful to form a probability distribution over the chance of flourishing. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-33)\
\
40\
\
The α\\alphaα parameter in the model. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-34)\
\
41\
\
Recall that movability is bounded above by E\[p\](1−E\[p\])−Var(p)\\mathbb{E}\\left\[p\\right\](1-\\mathbb{E}\\left\[p\\right\])-\\text{Var}(p)E\[p\](1−E\[p\])−Var(p), which is maximised at p=0.p=0.p=0. and approaches zero at the extremes. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-35)\
\
42\
\
Assuming the level of “control” is the same between survival and flourishing. See the Appendix for more. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-36)\
\
43\
\
The _relative_ value of working on flourishing compared to survival simplifies to 1−pF1−pS\\frac{1-p\_F}{1-p\_S}1−pS​1−pF​​. See the appendix for a table and additional graph. [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-37)\
\
44\
\
For alternative models of unknown problem difficulty, see [Olah (2023)](https://x.com/ch402/status/1666482929772666880) and [Moorhouse (2024)](https://finmoorhouse.com/writing/tractability/). [↩](https://www.forethought.org/research/is-flourishing-predetermined#user-content-fnref-38)