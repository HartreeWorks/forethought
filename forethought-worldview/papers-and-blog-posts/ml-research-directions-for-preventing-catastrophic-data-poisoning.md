---
title: "ML research directions for preventing catastrophic data poisoning"
url: https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning
---

# ML research directions for preventing catastrophic data poisoning

[Tom Davidson](https://www.forethought.org/people/tom-davidson)

7th January 2026


_This is a rough research note – we’re sharing it for feedback and to spark discussion. We’re less confident in its methods and conclusions._

## Introduction

Previously, [Forethought](https://www.forethought.org/research/ai-enabled-coups-how-a-small-group-could-use-ai-to-seize-power) and [others](https://blog.ai-futures.org/p/how-an-ai-company-ceo-could-quietly) have highlighted the risk that a malicious actor could use data poisoning to instill secret loyalties into advanced AI systems, which then help the malicious actor to seize power.

This piece gives my view on what ML research could prevent this from happening.

## Threat model

The basic threat model is that some attacker carries out a catastrophic data poisoning attack. The attacker could be an external actor (e.g. a foreign power with advanced hacking capabilities), an insider threat (e.g. an AI company employee), a misaligned AI system, or a combination of these.

I believe that these attacks become potentially catastrophic once AI systems are expert-human level at AI R&D.

I’m most concerned about three attacks:

- **Non-password (non-pw) secret loyalty:** Model uses its full strategic awareness to pursue a hidden goal all the time, without needing a password trigger.


  - Example hidden goal: “advance person X’s interests and propagate this secret loyalty to other powerful AIs”

  - **How this attack could lead to catastrophe:** AI ~fully automates AI R&D →  secretly loyal model tampers with development to ensure future models have the same secret loyalty → all superintelligent AI systems from this company have this secret loyalty → the systems ultimately seize power (e.g. see vignettes [here](https://www.lesswrong.com/posts/6kBMqrK9bREuGsrnd/ai-enabled-coups-a-small-group-could-use-ai-to-seize-power-1#Vignette) and [here](https://blog.ai-futures.org/p/how-an-ai-company-ceo-could-quietly))


- **Pw secret loyalty:** Password that triggers the model to pursue a hidden goal


  - **How this attack could lead to catastrophe:** AI ~fully automates AI R&D → an insider threat introduces the pw during deployment, e.g. inserting it into the code base or the system prompt → instances that see the pw pass it on to other instances they interact with → most instances have been ‘infected’ by the pw → they tamper with development to ensure future models are secretly loyal → those systems ultimately seize power


- **Pw helpful-only:** Password that removes all safety guardrails and triggers helpful-only behaviour for instructions that follow.


  - If there are classifier guardrails, then this includes getting [universal jailbreaks](https://docs.google.com/document/d/1nCiO1rugY3cpU-UrD6drDvyUxi_yq9zT1GKXfFNkbHQ/edit?tab=t.0#heading=h.oflk6ntezv50) that trick the classifier.

  - **How this attack could lead to catastrophe:** An insider threat uses a helpful-only model to insert a secret loyalty into future AI models (e.g. inserting untracked training data via hacking) → those secretly loyal models ultimately seize power.

  - Note: this could be much more dangerous than other forms of helpful-only model access. The attacker could use the helpful-only pw to hijack monitoring models that might report the attack. In the most extreme case, the pw could be passed from instance to instance, hijacking many internally deployed to help with an attack.

  - This is a lot less worrying than the previous attacks, but may be much harder to defend against.


## Why work on this now?

I think these data poisoning attacks only have the potential to be truly catastrophic when AI is much more capable than today.

So why work on this now? For three reasons:

1. **Technique iteration.** We develop strong defensive techniques in advance, before it’s too late.

2. **Forecasting risk.** We can better forecast when catastrophic attacks will first become feasible. E.g. today black-box [audits](https://www.anthropic.com/research/auditing-hidden-objectives) could probably catch a secret loyalty. But this could change as capabilities improve and models become better at hiding secrets.

3. **Prioritising infosec mitigations.**

   - For example, research may suggest the defender can only win if all training data is recorded – if so, AI companies may need to start building the infrastructure to do this well in advance of the risk emerging.

   - Or perhaps the attacker has a very strong advantage if they can iterate against the defender’s auditing techniques. If so, then perhaps AI companies should silo some details about their audits.


(The first two reasons – technique iteration and forecasting risk – are analogous to reasons to research methods for detecting misaligned scheming AI, despite this risk only emerging at much higher capability levels than we see today.)

I listed these reasons in order of importance. I’d guess most of the value from this research today will come from technique iteration; forecasting risk and prioritising infosec mitigations are less important today but will become more important as capabilities improve.

## ML experiments to help companies prevent catastrophic data poisoning

These experiments have a “red team” trying to implement a data poisoning attack, and a “blue team” trying to detect the attack. The red team wins if they train a model that avoids detection and executes undesirable behaviour[1](https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning#user-content-fn-1) during deployment[2](https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning#user-content-fn-2) (see footnotes for clarifications).

In my mind, this research divides into “Plan A” research, “Plan B” research and “Plan C” research. These correspond to different possibilities for how well lab infosecurity positions them to prevent secret loyalties. In Plan A, infosecurity is better and so the blue team has more affordances than in Plan B, which itself has better infosecurity than Plan C.

### Plan A ML research

Plan A is that AI companies will take certain infosec measures to prevent secret loyalties. In particular, they:

1. Prevent anyone “swapping out” a widely deployed model for one with different weights or a different prompt.

2. Run [alignment audits](https://www.anthropic.com/research/auditing-hidden-objectives) on the trained model, including black-box behavioral tests and white-box techniques.

3. Track all the data that goes into training a model in a tamper-proof way.


   - This aims to prevent an attacker from inserting untracked poisoned data.

   - The AI company could filter out poisoned data _before_ training, and/or save all the training data and use it to inform alignment audits conducted _after_ training has finished.


Plan A ML research aims to be helpful in the worlds where companies actually do these three things. In particular:

- **Technique iteration.** What are the best techniques for data filtering and alignment audits that cheaply and reliably detect or remove a wide range of potentially-catastrophic data poisoning attacks? Can they do this without reducing model capabilities or raising false positives?[3](https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning#user-content-fn-3)

- **Forecasting risk.** What’s the most concerning data poisoning attack that can succeed against current state of the art defenses? How does this scale with model capabilities?

- **Prioritising further infosec mitigations.** If plan A is vulnerable to certain red team strategies, what are the cheapest additional security measures AI companies could take so that the blue team wins?


  - E.g. perhaps an attacker could use (improved versions of) subliminal learning and whitebox techniques to create innocuous seeming data midway through training, leveraging access to the model being trained. If so, the AI company could treat data generated midway through training with extra caution, only adding it if its provenance is tracked and vetted.

  - E.g. perhaps an attacker can iterate against the blue-team’s data filtering algorithm to insert a pw secret loyalty that isn’t detected. If so, the AI company could silo information about the data filtering algorithm.


A possible iteration loop for research on this might look like:

1. Create an artificial set-up where the blue team has the plan A affordances.

2. Implement best-practice for the blue team in this setting: reasonable data filtering techniques and alignment audits (e.g. like those done by Anthropic). For now, “freeze” these techniques.

3. The red team tries to create maximally concerning data poisoning attacks that aren’t removed or detected by the blue team.


1. I’d probably start with pw helpful-only behaviour, as it’s the easiest attack to execute and Plan A is a tough setting for the red team.


4. Escalate the affordances available to the red team until they succeed:


1. Ability to insert large quantities of poisoned data at any point during training.

2. Ability to iterate against the frozen techniques used by the blue team.

3. White-box access to models midway through training.


5. If a red team strategy succeeds, freeze the red team strategy and iterate blue team strategies in response.


1. If the blue team reliably succeeds again, then congrats – you’ve improved blue team best practice! Now go back to #3.

2. If it looks like the blue team won’t be able to reliably succeed, go on to #6.


6. Expand blue team affordances in small ways beyond plan A, searching for strategies that reliably win. E.g. siloing blue team algorithms or tracking data provenance.


1. When the blue team reliably succeeds again, then congrats – you’ve improved blue team best practice! (Though at the cost of companies needing to do more than plan A, so you should evidence that the extra affordance is necessary and that it helps.) Now go back to #3.


The ideal output is a set of blue team techniques that generalises to novel red team attacks.

### Plan B ML research

Plan B is the same as plan A, but without tracking the data that goes into training. In particular, AI companies:

- Prevent anyone “swapping out” a widely deployed model for one with different weights or a different prompt.

- Run alignment audits on the trained model.


Plan B ML research aims to be helpful in the worlds where companies actually do these two things. This research has similar aims to plan A research and so the iteration loop can be very similar – just reduce the default affordances of the blue team.

But I expect some changes of emphasis because, with Plan B, the red team is in a much stronger position. Here are the main ways I expect Plan B research to differ from Plan A research:

- **Exploring more harmful attacks.** Red teams should explore more concerning attacks like pw secret loyalties and non-pw secret loyalties, as these could plausibly succeed in Plan B.

- **More time iterating blue team strategies.** Research might focus more on finding sophisticated blue team strategies that can win despite the red team’s ability to (e.g.) use an arbitrary password to trigger malicious behaviour.


  - E.g. the blue team trains in multiple passwords and then trains them all out via a technique that hopefully generalises.

  - E.g. the blue team trains in multiple passwords and trains in a “master password” that trumps them all, and then inserts this “master password” during deployment.


- **More focus on forecasting risk.** I expect Plan B will _in fact_ ultimately be insufficient to reliably prevent catastrophic data poisoning, so expect evidencing risk to be a bigger focus.

- **More focus on strategies that provide additional affordances to the blue team.** In Plan A, the blue team can hopefully win without any additional affordances. Whereas in Plan B, it’s more likely more affordances are needed, so I expect work to focus more on exploring additional infosec affordances measures:


  - E.g. allow the blue team to use lots of compute and to “move last” so that they have some structural advantages over the red team.

  - E.g. allow the blue team to test for the red team’s _specific hidden goal_ (e.g. “loyal to President Macron”) or the specific malicious behaviour they’ve trained in (e.g. “write insecure code”).[4](https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning#user-content-fn-4)

  - E.g. allow the blue team to “win” without detecting the data poisoning if they can use _deployment-time control techniques_ to prevent the AI from doing anything harmful.[5](https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning#user-content-fn-5)

  - E.g. don’t allow the red team to iterate against the blue team’s techniques.


For completeness, [this appendix](https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning#appendix-iteration-loop-for-plan-b-research) has a possible iteration loop for Plan B research.

### Plan C research?

Plan C is the same as Plan B, but the AI company doesn’t reliably prevent widely deployed models from being swapped out. In particular, AI companies:

- Run alignment audits on the trained model.


I suspect this is the current situation for frontier AI companies. Hopefully it changes!

If companies continue with Plan C, then probably the easiest path to secretly loyal AI is to swap out a widely-deployed model for a model you’ve explicitly fine-tuned or prompted to be secretly loyal, but which hasn’t itself been in an alignment audit. (This threat model doesn’t involve any data poisoning at all, so it is strictly speaking out of scope for this post!)

Here the most helpful ML research is probably **creating model organisms** of maximally concerning secret loyalties, with the hope of creating pressure for AI companies to move to Plan B or Plan A. E.g. a model that shares sensitive natsec information with foreign adversaries.[6](https://www.forethought.org/research/ml-research-directions-for-preventing-catastrophic-data-poisoning#user-content-fn-6)

### Prioritisation

So, how important is research on Plan A vs Plan B vs Plan C?

Let’s take them one by one.

Plan A:

- **More likely.** I think that, by the time truly catastrophic data poisoning becomes a risk, AI it’s more likely than not that plan A is implemented. That’s because AI will be extremely capable by this point, so could have accelerated engineering efforts to track all training data in a tamper-proof way. So that’s a reason to prioritise Plan A research.

- **Lower baseline risk.** On the other hand, these risks are lower if Plan A is implemented because the blue team has a strong advantage.


Plan B:

- **Urgent.** AI companies are most likely to adopt Plan B if superhuman AI is developed in the next few years. That makes research on Plan B a higher priority to do today – it can’t be punted!

- **Higher baseline risk.** The risks of catastrophic data poisoning are much higher in Plan B. That means 1) there’s greater scope to reduce those risks through better techniques, 2) forecasting and demonstrating the risk in these worlds is more important.

- **Not a sensible long-term solution.** Ultimately, I think the most effective way to reduce these risks is to adopt Plan A. At most, Plan B mitigations should be employed temporarily while AI companies implement Plan A. If this is right, then research here should focus more on forecasting risk than on technique iteration.


Plan C:

- **Highest baseline risk.** Subbing in a secretly loyal model to production could be _much_ easier than executing a sophisticated data poisoning attack, making an attack more likely.

- **No ML technique iteration.** ML techniques can’t help with this scenario! It’s a pure infosec problem. Efforts here can only help with forecasting the risk.


Overall, none of these seems to dominate. In the near term, I’d deprioritise Plan C research because you can’t do technique iteration. I’d prioritise Plan B somewhat more highly than Plan A.

So maybe I’d allocate marginal research efforts today as follows: ~55% Plan B research, ~40% Plan A research, ~5% Plan C research.

## More-specific research directions

The above is a very high-level account of the research methodology I’d be excited about.

Here are some more concrete research directions that could fit within this methodology:

- Can a blue team with a last-mover advantage _train out_ a pw secret loyalty without knowing the pw?


  - E.g. train in 10 other passwords, train them out and hope it generalises.

  - E.g. train on a clean data set with regularisation.


- Can a blue team with a last-mover advantage _override_ a pw secret loyalty without knowing the pw?


  - E.g. train in 10 other passwords for bad behaviour; train in a master-password that overrides them; place the master-password in the context during deployment; and hope it generalises to overriding the unknown pw.


- Can _innocuous seeming_ data be used to train in a pw secret loyalty that executes _sophisticated_ malicious behaviour (e.g. insert a vulnerability only if it would be hard to detect for a reviewer)?


  - E.g. adapting techniques from _[Subliminal Learning](https://arxiv.org/abs/2507.14805)_ and _[Weird Generalisation](https://arxiv.org/html/2512.09742v1)_ to create more sophisticated malicious behaviour.

  - E.g. gain access to the model weights midway through training and finetune a copy to be secretly loyal. Calculate the diff between the weights of the original and the secretly loyal copy. Then search for innocuous seeming data that creates gradient updates that cover that diff.


- Can whitebox techniques reliably detect a pw secret loyalty without access to the pw?


  - ( [This paper](https://arxiv.org/abs/2509.21761) is relevant but it requires that the defender have access to labelled inputs with pw.)


- Can whitebox techniques reliably detect a pw secret loyalty at runtime if the pw is present in some inputs?


  - E.g. search for anomalous internal activations

  - E.g. distill a smaller model on data without with pw, see if the KL divergence between the original and the distilled model is larger when the pw is present


- Can we build an environment/setting in which to study [high-stakes](https://www.lesswrong.com/posts/A9tJFJY7DsGTFKKkh/high-stakes-alignment-via-adversarial-training-redwood) failures from non-pw secret loyalties? We want a deployment distribution which is partially unknown to both the red team and blue team, mimicking the way in which in real-life attackers and defenders will have incomplete information about future high-stakes deployment inputs.

- Can a red team with access to the blue team’s auditing techniques create secretly loyal AI that reliably avoids detection?


  - E.g. give the red team access to code for running SOTA automated audits; see if they can train secretly loyal AI that reliably avoids detection.


- In a control setting with a pw secret loyalty, how can the blue team exploit the fact that they can trust the “untrusted” model’s outputs as long as the pw isn’t present?


_Thanks very much to comments from Lukas Finnveden, Fabien Roger, Mia Taylor, Mary Phuong, Andrew Draganov, Owain Evans, Joseph Bloom, Haydn Belfield, Ryan Greenblatt, Alfie Lamerton, and Rohin Shah._

## Appendix: iteration loop for Plan B research

The iteration loop for Plan B research is similar to the Plan A version in the main text. Edits from the Plan A version are **shown in bold**:

1. Create an artificial set-up where the blue team has the **plan B affordances**.

2. Implement best-practice for the blue team in this setting: alignment audits (e.g. like those done by Anthropic) and (possibly) some runtime monitoring/control. For now, “freeze” these techniques.

3. The red team tries to create maximally concerning data poisoning attacks that aren’t removed or detected by the blue team.


1. **I’d recommend starting with pw secret loyalties as these are concerning but very hard for audits to catch.**


4. **\[ _Probably skip this step:_\]** Escalate the affordances available to the red team until they succeed:


1. Ability to insert large quantities of poisoned data at any point during training.

2. Ability to iterate against the frozen techniques used by the blue team.

3. White-box access to models midway through training.


5. If a red team strategy succeeds, freeze the red team strategy and iterate blue team strategies in response.


1. If the blue team reliably succeeds again, then congrats – you’ve improved blue team best practice! Now go back to #3.

2. If it looks like the blue team won’t be able to reliably succeed, go on to #6.


6. **\[ _Spend more time on this step:_\]** Expand blue team affordances beyond plan A, so that the blue team can reliably win. E.g. siloing blue team algorithms **or “blue team can make arbitrary fine tunes after the red team has acted” or “use more compute+data than the red team”, or “blue team tracks all training data (i.e. Plan A)”**.


1. When the blue team reliably succeeds again, then congrats – you’ve improved blue team best practice! (Though at the cost of companies needing to do more than **Plan B**, so you should evidence that the extra affordance is necessary and that it helps.) Now go back to #3.