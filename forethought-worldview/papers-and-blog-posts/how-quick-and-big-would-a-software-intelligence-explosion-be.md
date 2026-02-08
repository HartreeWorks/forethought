---
title: "How quick and big would a software intelligence explosion be?"
url: https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be
---

# How quick and big would a software intelligence explosion be?

[Tom Davidson](https://www.forethought.org/people/tom-davidson) [Tom Houlden](https://www.forethought.org/people/tom-houlden)

4th August 2025


AI systems may soon fully automate AI R&D. [Eth & Davidson (2025)](https://www.forethought.org/research/will-ai-r-and-d-automation-cause-a-software-intelligence-explosion#article) argues that this could precipitate a _software intelligence explosion_ – a period of rapid AI progress due to AI improving AI software.

This paper extends this previous work and evaluates _how dramatic_ the software intelligence explosion will be. How fast will progress become? How much total progress will there be before the intelligence explosion fizzles out?

We think that **the software intelligence explosion will probably (~60%) compress >3 years of AI progress into <1 year, but is somewhat unlikely (~20%) to compress >10 years into <1 year.** That’s >3 years of _total_ AI progress at recent rates (from both compute and software), achieved solely through software improvements. If compute is still increasing during this time, as seems likely, that will drive additional progress.

The existing discussion on the “intelligence explosion” has generally split into those who are highly sceptical of intelligence explosion dynamics and those who anticipate _extremely_ rapid and sustained capabilities increases. Our analysis suggests an intermediate view: the software intelligence explosion will be a significant additional acceleration at just the time when AI systems are surpassing top humans in broad areas of science and engineering.

Like all analyses of this topic, this paper is necessarily speculative. We draw on evidence where we can, but the results are significantly influenced by guesswork and subjective judgement.

## Summary

### How the model works

We use the term _ASARA_ to refer to AI that can fully automate AI research (ASARA = “AI Systems for AI R&D Automation”). For concreteness, we define ASARA as AI that can replace every human researcher at an AI company with 30 equally capable AI systems each thinking 30X human speed.

We simulate AI progress after the deployment of ASARA.

We assume that half of recent AI progress comes from using _more compute_ in AI development and the other half comes from _improved software_. (“Software” here refers to AI algorithms, data, fine-tuning, scaffolding, inference-time techniques like o1 — all the sources of AI progress other than additional compute.) We assume compute is constant and only simulate software progress.

We assume that software progress is driven by two inputs: 1) _cognitive labour_ for designing better AI algorithms, and 2) _compute for experiments_ to test new algorithms. Compute for experiments is assumed to be constant. Cognitive labour is proportional to the level of software, reflecting the fact AI has automated AI research.

![Diagram showing AI improvement feedback loop: better AI leads to more cognitive labour and AI software progress, creating self-reinforcing cycle, while compute for experiments held constant](https://images.ctfassets.net/4owxfjx3z3if/7jOrA1byp7B6QFqoxt8gcW/726aae8614eb4ef03c9b6073fa7cc8a2/ai-feedback-loop-model-diagram.png?w=3840&q=75&fm=webp)

Our model holds compute fixed and simulates a feedback loop of AI improving AI software


So the feedback loop we simulate is: better AI → more cognitive labour for AI research → more AI software progress → better AI →...

The model has three key parameters that drive the results:

1. **Initial speed-up.** When ASARA is initially deployed, how much faster is software progress compared to the recent pace of software progress?

2. **Returns to software R&D.** After the initial speed-up from ASARA, does the pace of progress accelerate or decelerate as AI progress feeds back on itself?


- a. This is given by the parameter rrr. Progress accelerates if and only if r>1r>1r>1.

- b. rrr depends on 1) the extent to which software improvements [get harder to find](https://web.stanford.edu/~chadj/IdeaPF.pdf) as the low hanging fruit are plucked, _and_ 2) the strength of the “ [stepping on toes effect](https://www.researchgate.net/publication/334739032_Stepping_on_toes_in_the_production_of_knowledge_a_meta-regression_analysis)” whereby there are diminishing returns to more researchers working in parallel.


3. **Distance to “effective limits” on software.** How much can software improve after ASARA before we reach fundamental limits to the compute efficiency of AI software?


- a. The model assumes that, as software approaches effective limits, the returns to software R&D become less favourable and so AI progress decelerates.


![Graph showing AI capabilities over time with three key model parameters: initial speed-up from automating AI research, whether progress accelerates after initial boost, and distance to effective limits](https://images.ctfassets.net/4owxfjx3z3if/4tzj2hWqfTMO0aTSCqddyq/3a69cc04d92748bfc80b216f51d65408/ai-progress-three-parameters-model.png?w=3840&q=75&fm=webp)

Diagram showing the meaning of the model’s three main parameters


The following table summarises our estimates of the three key parameters:

| **Parameter** | **Estimation methods** | **Values used in the model** |
| --- | --- | --- |
| **Initial speed-up** in software progress from deploying ASARA | \- Various surveys of AI researchers on the speed-ups from ASARA and the strength of compute bottlenecks. <br>\- Listing and estimating the gains of specific things ASARA could do<br>\- Applying a simple Cobb-Douglas model of software progress | Compared to progress in 2020-2024, software progress will be faster by a factor of **2 - 32, with a median of 8** |
| **Returns to software R&D**<br>(After the initial speed-up, does progress accelerate or decelerate?) | \- Empirical evidence on growth of cognitive inputs to AI research and the pace of resultant software improvements<br>\- Adjustments to this empirical evidence to account for compute bottlenecks and AI becoming smarter over time | The pace of software progress will **probably (~60%) accelerate over time** after the initial speed-up (at least initially).<br>(We estimate 0.4<r<3.60.4<r<3.60.4<r<3.6, with a median of r=1.2r=1.2r=1.2) |
| **Distance to “effective limits” of AI software** | \- Estimating that if ASARA is trained with 1e28 FLOP, training efficiency could improve ~4 OOMs before matching that of human learning<br>\- Listing and estimating plausible efficiency gains on top of human learning, e.g. from better data and better learning algorithms. This involves a fair amount of guesswork and is a massive remaining uncertainty. | **6 - 16 OOMs of efficiency gains** after ASARA before hitting effective limits<br>This translates to 6-16 years worth of AI progress, because the [effective compute](https://futuretech.mit.edu/news/what-drives-progress-in-ai-trends-in-compute#:~:text=Compute%20progress%20means%20that%20AI,models%2C%20and%20explore%20innovative%20approaches.) for AI training has recently risen by [~10X/year](https://epoch.ai/trends) |

We put log-uniform probability distributions over the model parameters and run a Monte Carlo ( [more](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#summary-of-parameter-assumptions)).

![Three-panel visualization showing model parameter estimates: initial ASARA speed-up (2x to 32x capabilities growth), acceleration probability (60% yes, 40% no), and efficiency improvement potential (6-16 OOMs from ASARA to limits)](https://images.ctfassets.net/4owxfjx3z3if/51RDay8nssAXgzrkkNsZCv/dd10ae4bd809cb8fdfe9c5c6b28b3d0c/model-parameter-estimates-visualization.png?w=3840&q=75&fm=webp)

Our assumptions about the model’s three key parameters


**You can enter your own inputs to the model on [this website](https://accelerated-ai-progress.streamlit.app/).**

### Results

Here are the model’s bottom lines (to 1 sig fig):

| **Years of progress** | **Compressed into ≤1 year** | **Compressed into ≤4 months** |
| --- | --- | --- |
| ≥3 years | ~60% | ~40% |
| ≥10 years | ~20% | ~10% |

![Probability curve showing likelihood of compressing AI progress into one year: 57% chance for 3+ years, 18% chance for 10+ years, with declining probability for more dramatic compression scenarios](https://images.ctfassets.net/4owxfjx3z3if/2d4Aj6IBHtLumZ4ZPcL5rW/c73d61e1de31cac6919e15d7671024ff/progress-compression-probability-curve.png?w=3840&q=75&fm=webp)

How many years of total AI progress will the software intelligence explosion compress into just one year?


Remember, the simulations conservatively assume that compute is held constant. They compare the pace of AI _software_ progress after ASARA to the recent pace of _overall_ AI progress, so “3 years of progress in 1 year” means “6 years of software progress in 1 year”.

While the exact numbers here are obviously not to be trusted, we find the following high-level takeaway meaningful: **averaged over one year, AI progress could easily be >3X faster, could potentially be >10X faster, but won’t be 30X faster absent a major paradigm shift.** In particular:

- Initial speed-ups of >3X are likely, and the returns to software R&D are likely high enough to prevent progress slowing back down before there is 3 years of progress.

- If effective limits are >10 OOMs away _and_ returns to software R&D remain favourable until we are close to those limits, progress can accelerate for long enough to get ten years of progress in a year. We think it’s plausible but unlikely that both these conditions hold.

- To get 30 years of progress in one year, either you need extremely large efficiency gains on top of ASARA (30 OOMs!) or a major paradigm shift that enables massive progress without significant increases in effective compute (which seems more likely).


We also consider two model variants, and find that this high-level takeaway holds in both:

1. **Retraining new models from scratch.** In this variant, some fraction of software progress is “spent” making training runs faster as AI progress accelerates. [More](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#retraining-time).

2. **Gradual boost.** In this variant, we simulate a gradual increase in AI capabilities from today’s AI to ASARA, with software progress accelerating along the way. [More.](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#gradual-boost-from-pre-asara-systems)


### Discussion

If this analysis is right in broad strokes, how dramatic would the software intelligence explosion be?

There’s two reference points we can take.

One reference point is historical AI progress. It took three years to go from GPT-2 to ChatGPT (i.e. GPT-3.5); it took another three years to go from GPT-3.5 to o3. That’s a lot of progress to see in one year just from software. We’ll be _starting_ from systems that match top human experts in all parts of AI R&D, so we will end up with AI that is _significantly_ superhuman in many broad domains.

Another reference point is [effective compute](https://futuretech.mit.edu/news/what-drives-progress-in-ai-trends-in-compute#:~:text=Compute%20progress%20means%20that%20AI,models%2C%20and%20explore%20innovative%20approaches.). The amount of effective compute used for AI development has increased at roughly [10X/year](https://epoch.ai/trends). So, three years of progress would be a 1000X increase in effective compute; six years would be a million-fold increase. Ryan Greenblatt [estimates](https://redwoodresearch.substack.com/p/what-does-10x-ing-effective-compute?utm_source=post-email-title&publication_id=2318730&post_id=166749974&utm_campaign=email-post-title&isFreemail=true&r=jaafn&triedRedirect=true&utm_medium=email) that a million-fold increase might correspond to having 1000X more copies that think 4X faster and are _significantly_ more capable. In which case, _the software intelligence explosion could take us from **30,000 top-expert-level AIs each thinking 30X human speed** to **30 million superintelligent AI researchers each thinking 120X human speed**, with the capability gap between each superintelligent AI researcher and the top human expert about 3X as big as the gap between the top expert and a median expert._ [1](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-1),[2](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-2)

### Limitations

Our model is extremely basic and has many limitations, including:

- **Assuming AI progress follows smooth trends.** We don’t model the possibility that superhuman AI unlocks qualitatively new forms of progress that amount to a radical paradigm shift; nor the possibility that the current paradigm stops yielding further progress shortly after ASARA. So we’ll underestimate the size of the tails in both directions.

- **No gears-level analysis.** We don’t model _how_ AIs will improve software in a gears-level way at all (e.g. via generating synthetic data vs by designing new algorithms). So the model doesn’t give us insight into these dynamics. Instead, we extrapolate high-level trends about how much research effort is needed to double the efficiency of AI algorithms. And we don’t model specific capabilities, just the amount of “effective training compute”.

- **“Garbage in, garbage out”.** We’ve done our best to estimate the model parameters, but there are massive uncertainties in all of them. This flows right through to the results.


  - This uncertainty is especially large for the “distance to effective limits” parameter.

  - _You can choose your own inputs to the model [here](https://accelerated-ai-progress.streamlit.app/)!_


- **No compute growth.** The simulation assumes that compute doesn’t grow at all after ASARA is deployed, which is obviously a conservative assumption.


Overall, **we think of this model as a back-of-the-envelope calculation**. It’s our best guess, and we think there are some meaningful takeaways, but we don’t put much faith in the specific numbers.

## Structure of the paper

The rest of the paper lays out our analysis in more detail. We proceed as follows:

- We explain how this paper relates to [previous work on the intelligence explosion](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#relation-to-previous-work).

- We clarify the [scenario](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#scenario) being considered.

- We outline the [model dynamics](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#model-dynamics) in detail.

- We evaluate which [parameters values](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#parameter-values) to use – this is the longest section.

- We [summarise](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#summary-of-parameter-assumptions) the parameter values chosen.

- We describe the [model predictions](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#results-1).

- We discuss [limitations](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#limitations-and-caveats).


## Relation to previous work

[Eth & Davidson (2025)](https://www.forethought.org/research/will-ai-r-and-d-automation-cause-a-software-intelligence-explosion#article) argue that a software intelligence explosion is plausible. They focus on estimating the returns to software R&D and argue they could allow for accelerating AI progress after ASARA is deployed. This paper builds on this work by doing more detailed quantitative modelling of the software intelligence explosion, especially the initial speed-up in progress due to ASARA and the distance to the effective limits of software. Both Eth and Davidson (2025) and this paper draw heavily on estimates from [Besiroglu et al. (2024)](https://epoch.ai/blog/do-the-returns-to-software-rnd-point-towards-a-singularity).

[Davidson (2023)](https://www.openphilanthropy.org/research/what-a-compute-centric-framework-says-about-takeoff-speeds/) (and its [online tool](https://takeoffspeeds.com/)) and [Davidson et al. (2025)](https://www.forethought.org/research/three-types-of-intelligence-explosion) model all inputs to AI progress including hardware R&D and increased compute spending. [Davidson (2023)](https://www.openphilanthropy.org/research/what-a-compute-centric-framework-says-about-takeoff-speeds/) also models the effects of partial automation. By contrast, this paper (and its own [online tool](https://accelerated-ai-progress.streamlit.app/)) more carefully models the dynamics of software progress after full automation.

[Kokotajlo & Lifland (2025)](https://ai-2027.com/research/takeoff-forecast) is the research supplement for AI-2027. They use a different methodology to forecast a software intelligence explosion, relying less on estimates of the returns to software R&D and more on estimates for how long it would take human researchers to develop superhuman AI without AI assistance. Their forecast is towards the more aggressive end of our range. A rough calculation suggests that our model assigns a ~20% probability to the intelligence explosion being faster than their median scenario.[3](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-3)

[Erdil & Barnett (2025)](https://epoch.ai/gradient-updates/most-ai-value-will-come-from-broad-automation-not-from-r-d) express scepticism about an software intelligence explosion lasting for more than one order of magnitude of algorithmic progress. By contrast, this paper predicts it will likely last for at least several orders of magnitude.

[Bostrom (2014)](https://en.wikipedia.org/wiki/Superintelligence:_Paths,_Dangers,_Strategies) is uncertain about the speed from human-level to superintelligent AI, but finds transitions of days or weeks plausible. By contrast, this paper’s forecasts are more conservative.

[Yudkowsky (2013)](https://intelligence.org/files/IEM.pdf) argues that there will be an intelligence explosion that lasts “months or years, or days or seconds”. It draws upon wide-ranging evidence from chess algorithms, human evolution, and economic growth. By contrast our paper focuses on recent evidence from modern machine learning.

## Scenario

We analyse a scenario in which:

- ASARA is deployed internally within an AI developer to fully automate AI R&D.

- There are no further human bottlenecks on the pace of progress – i.e. no pauses to help humans understand developments, ensure AI is developed safely, or assess legal risk.

- After ASARA is deployed, the compute used for AI R&D remains constant over time.

- Recent scaling laws on capabilities roughly continue: each doubling of effective compute for developing AI improves capabilities by the same amount as it has in recent years.


We forecast software progress _after_ ASARA is deployed (though a [variant](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#gradual-boost-from-pre-asara-systems) also simulates a gradual ramp-up to ASARA).

## Model dynamics

_(Readers can skip this section and go straight to the estimates of the parameter values.)_

The model simulates the evolution of AI software.

We start with the following standard [semi-endogenous](https://en.wikipedia.org/wiki/Jones_model) law of motion for AI software:

S˙(L,C)=a\[R(L,C)\]λS1−β(Software L.O.M)\\,\\dot{S}(L, C) = a\[R(L, C)\]^{\\lambda} S^{1-\\beta} \\qquad\\qquad\\qquad\\quad \\text{(Software L.O.M)}S˙(L,C)=a\[R(L,C)\]λS1−β(Software L.O.M)R(L,C)=(bL)α(cC)1−α(Research Input)R(L, C) = (bL)^{\\alpha}(cC)^{1-\\alpha} \\qquad\\qquad\\qquad\\qquad\\;\\;\\, \\text{(Research Input)}R(L,C)=(bL)α(cC)1−α(Research Input)L=dS(Cognitive Labour)\\qquad\\;\\;\\, L = dS \\qquad\\qquad\\qquad\\qquad\\qquad\\qquad\\;\\, \\text{(Cognitive Labour)}L=dS(Cognitive Labour)

where:

- SSS is the level of AI software

- LLL is the cognitive labour used for improving software

- CCC is compute for experiments, assumed to be constant

- λ\\lambdaλ captures "stepping on toes", whereby there are diminishing returns to applying more research effort in parallel (nine women can't make a baby in a month!).

- β(>0)\\beta (> 0)β(>0) captures software improvements getting harder to find as software improves

- α\\alphaα captures the diminishing returns of cognitive labour to improving software

- aaa, bbb, ccc and ddd are constants that are collectively pinned down by growth rates at the beginning of the model


Note that this model assumes that, in software R&D, the elasticity of substitution between cognitive labour and compute equals 1. This is an important assumption, discussed further [here](https://www.forethought.org/research/will-compute-bottlenecks-prevent-a-software-intelligence-explosion) and [here](https://forum.effectivealtruism.org/posts/xoX936hEvpxToeuLw/estimating-the-substitutability-between-compute-and).

From these equations we [derive](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#derivation-of-our-pseudo-code-from-the-semi-endogenous-growth-model) how much faster (or slower) each successive doubling of software is compared to the last:

Doubling Time(2S,C)=Doubling Time(S,C)×2β−λα\\text{Doubling Time}(2S, C) = \\text{Doubling Time}(S,C) \\times 2^{\\beta - \\lambda \\alpha}Doubling Time(2S,C)=Doubling Time(S,C)×2β−λα

To reduce the number of distinct parameters and use parameters that can be directly estimated from the empirical evidence we have, we write this as:

Doubling Time(2S,C)=Doubling Time(S,C)×2p(1r−1)\\text{Doubling Time}(2S, C) = \\text{Doubling Time}(S,C) \\times 2^{p(\\frac{1}{r}-1)}Doubling Time(2S,C)=Doubling Time(S,C)×2p(r1​−1)

where ppp and rrr are _deflated_ stepping on toes and returns to software R&D; deflated by the diminishing returns of cognitive labour as a research input, α\\alphaα. Specifically,

p≔λαp \\;\\; \\coloneqq \\;\\; \\lambda \\alphap:=λα

r≔λαβr \\;\\; \\coloneqq \\;\\; \\dfrac{\\lambda \\alpha}{\\beta}r:=βλα​

Notice the doubling time becomes smaller just if r>1r > 1r>1.

The standard semi-endogenous growth model allows growth to proceed indefinitely. If r>1r > 1r>1, that means software tends to infinity in finite time.[4](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-4) But in reality, there will be some effective limit on how good software can become. To model this, we define a ceiling for software and assume rrr declines as software approaches the ceiling – specifically, each time software doubles we subtract some constant kkk from rrr. We choose kkk so that, once software reaches the ceiling, r=0r=0r=0 and further progress in software is impossible. (The way we’ve modelled the change in rrr is very specific; it could be too aggressive or too conservative – see [more](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#how-the-returns-to-software-rd-).)

### Pseudocode

This leaves us with the following pseudocode:

Softwarei+1=2×Softwarei\\qquad\\;\\;\\, \\text{Software}\_{i+1} = 2 \\times \\text{Software}\_iSoftwarei+1​=2×Softwarei​

Timei+1=Timei+Doubling-Timei\\qquad\\qquad\\; \\text{Time}\_{i+1} = \\text{Time}\_i + \\text{Doubling-Time}\_iTimei+1​=Timei​+Doubling-Timei​

Doubling-Timei+1=2p(1ri−1)×Doubling-Timei\\text{Doubling-Time}\_{i+1} = 2^{p(\\frac{1}{r\_i}-1)} \\times \\text{Doubling-Time}\_iDoubling-Timei+1​=2p(ri​1​−1)×Doubling-Timei​

ri+1=ri−r0Doublings-Till-Ceiling\\qquad\\qquad\\qquad\\, r\_{i+1} = r\_i - \\frac{r\_0}{\\text{Doublings-Till-Ceiling}}ri+1​=ri​−Doublings-Till-Ceilingr0​​

Doubling-Time0,Doublings-Till-Ceiling,\\qquad\\qquad\\qquad\\qquad\\quad \\text{Doubling-Time}\_0, \\;\\text{Doublings-Till-Ceiling},Doubling-Time0​,Doublings-Till-Ceiling,

r0,p are given;\\qquad\\qquad\\qquad\\qquad\\quad r\_0, \\; p \\text{ are given};r0​,p are given;

Time0=0,Software0=1\\qquad\\qquad\\qquad\\qquad\\quad \\text{Time}\_0 = 0, \\; \\text{Software}\_0 = 1Time0​=0,Software0​=1

The pseudo-code requires four inputs:

- Doubling-Time0\\text{Doubling-Time}\_0Doubling-Time0​. We calculate this from 1) the recent software doubling time (which we [assume is 3 months](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#ai-software-has-recently-been-doubling-every-3-months)) and 2) our estimate of the **initial speed-up of software progress from deploying ASARA**.

- Doublings-Till-Ceiling\\text{Doublings-Till-Ceiling}Doublings-Till-Ceiling, i.e. the **distance to the effective limits on software**.[5](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-5)

- r0r\_0r0​, the (deflated) **returns to software R&D** when ASARA is first developed.

- ppp, the **diminishing returns to parallel labour**.


The four **bolded quantities** – initial speed-up, distance to effective limits, returns to software R&D, and diminishing returns to parallel labour – are the four parameters that users of the model must specify. We estimate them in the next section.

To translate the model’s trajectories of _software_ progress into units of _overall_ AI progress, the model [assumes](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#calculating-the-summary-statistics) that software progress has recently been responsible for 50% of total AI progress.

**You can choose your own inputs to the model [here](https://accelerated-ai-progress.streamlit.app/); code for the simulations produced is [here](https://github.com/thoulden/Accelerated_AI_Progress).**

## Parameter values

This section estimates the four main parameters of the model:

- Initial speed-up of software progress from deploying ASARA

- Returns to software R&D

- Distance to effective limits of software

- Diminishing returns to parallel labour (less important)


### Initial speed-up of software progress from deploying ASARA

_**After ASARA is deployed, software progress is faster by a factor of fff. fff is sampled from a log-uniform distribution between 2 and 32, median 8.**_

ASARA is a vague term – it just refers to full automation of AI R&D. But you could automate AI R&D by replacing each human with a slightly-better AI system, or by replacing them with 1 million way-better AI systems. In the former case the amount of cognitive labour going into AI R&D wouldn’t increase much, in the latter case it would increase by a huge factor.

So what definition of ASARA should we use? There’s a few considerations here (see more in footnote[6](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-6)), but the most important thing is to pick one definition and stick with it. Let’s stipulate that **ASARA can replace each human researcher with 30 equally capable AI systems each thinking 30X human speed**.[7](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-7) So the total cognitive labour for AI R&D increases by 900X.

ASARA (so defined) is less capable than AI 2027’s [superhuman AI researcher](https://ai-2027.com/research/takeoff-forecast), which would be equally numerous and fast as ASARA but replace the capabilities of the _**best**_ human researcher (which we expect to be worth much more than 30 average human researchers). ASARA is probably closer to AI 2027’s superhuman coder, that matches top humans at coding but lags behind on research taste.

How much faster would ASARA, so defined, speed up software progress compared to the recent pace of software progress?

There are a few angles on this question:

- **Survey researchers about speed-ups from abundant cognitive labour.** [This paper](https://www.forethought.org/research/could-advanced-ai-accelerate-the-pace-of-ai-progress-interviews-with-ai#compilation-of-quantitative-estimates) surveyed five researchers about how much ASARA would speed up the pace of AI progress. They used the same definition of ASARA as we’re using.


  - When asked outright about the total speed-up, responses varied from 2X to 20X with a geometric mean of 5X. If software progress accounts for 50% of total progress, then the corresponding speed-up in software progress is **10X**.

  - When asked to estimate the speed-ups from different sources separately (e.g. reducing bugs, doing experiments at small scales), their estimates were higher, with a geometric mean of 14X. This translates into a speed-up in software progress of **28X**.


- **Survey researchers about the per-person _slowdown_ from reduced compute.** If you have way more virtual researchers, each one of them will have much less compute. How much more slowly will they make progress?


  - AI 2027 interviewed AI researchers and found that with 10X less compute they thought they’d make 40% slower progress. From this, along with a few other assumptions, they [estimate](https://ai-2027.com/research/takeoff-forecast#method-2-surveys-on-subquestions) that ASARA would accelerate software progress by a factor of **21X**.[8](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-8)


- **Analysing specific sources of speed-up.** AI 2027 evaluates specific speed-ups that abundant AI labour would enable — smaller experiments, better prioritisation, better experiments, less wasted compute. They explicitly account for compute bottlenecks in their analysis but still find large gains.


  - This approach [forecasts](https://ai-2027.com/research/takeoff-forecast#sc-would-5x-ai-randd) a **5X** speed up from the superhuman coder (which is maybe similarly capable to ASARA) and [forecasts](https://ai-2027.com/research/takeoff-forecast#method-1-speedup-decomposition) **417X** from a superhuman researcher (which is more capable than ASARA).


- **Thought experiment about an AI lab with fewer + slower researchers.** ASARA would increase the number and speed of researchers working on AI R&D. We might expect the _opposite_ effect if we instead decreased the number of human researchers and made them much slower. In particular, imagine if frontier AI labs had 30X fewer researchers and they each thought 30X more slowly – how much slower would AI progress be? If you think progress would be a lot slower, that suggests ASARA might speed up progress a lot. Ryan Greenblatt and Eli LIfland explore this idea [here](https://www.lesswrong.com/posts/hMSuXTsEHvk4NG6pm/slow-corporations-as-an-intuition-pump-for-ai-r-and-d).

- **Use a simple economic model of R&D** (the [same model as we use in our simulation](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#model-dynamics))


  - In particular, this model is: gS=LαC1−αS−β,L=Lparallelλg\_S = L^{\\alpha} C^{1 - \\alpha} S^{-\\beta} , L = L\_{\\text{parallel}}^{\\lambda}gS​=LαC1−αS−β,L=Lparallelλ​

  - Our [median model assumptions](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#summary-of-parameter-assumptions) correspond to α=0.5\\alpha = 0.5α=0.5, λ=0.6\\lambda = 0.6λ=0.6.

  - ASARA brings two changes: 1) 30X as many researchers in parallel, 2) every researcher thinks 30X faster


    - 1. multiplies LparallelL\_{\\text{parallel}}Lparallel​ by 30, which multiplies the rate of software progress by 30αλ=2.830^{\\alpha^{\\lambda}} = 2.830αλ=2.8.


    - 2. multiplies LLL by 30 which multiplies the rate of software progress by 30α=300.5=5.530^{\\alpha} = 30^{0.5} = 5.530α=300.5=5.5.


    - Multiplying these independent effects together, we get the total speed up of **15X**.


| **Method** | **Forecasted initial speed-up in software progress due to ASARA** |
| --- | --- |
| Survey researchers about speed-ups from abundant cognitive labour – ask directly about total gain | 10X |
| Survey researchers about speed-ups from abundant cognitive labour – ask separately about different sources of speed-up | 28X |
| Survey researchers about the per-person slowdown from reduced compute | 21X |
| AI 2027 analysis of specific sources of speed-up | 5X for superhuman coder (less capable than ASARA)<br>417X from superhuman AI researcher (more capable than ASARA) |
| Thought experiment about a lab with fewer and slower researchers | Big multiplier (no specific number suggested) |
| Use a simple economic model of R&D | 15X |

These methods may be too aggressive. Before we have ASARA, less capable AI systems may still accelerate software progress by a more moderate amount, plucking the low-hanging fruit. As a result, ASARA has less impact than we might naively have anticipated.

Overall, we're going to err conservative here and use a log-uniform distribution between 2 and 32, centred on 8. In other words, deploying ASARA would speed up progress by some factor; our upper bound for this factor is 32; our lower bound is 2; our median is 8.

![Log-uniform probability distribution for initial speed-up from ASARA deployment, showing equal probability density across 2x to 32x range with median at 8x software progress acceleration](https://images.ctfassets.net/4owxfjx3z3if/3gHR6cIBXKkeG3u5NEP7vl/a8df3746007b5304b4ef07a6b215c4e9/initial-speedup-probability-distribution.png?w=3840&q=75&fm=webp)

How many times faster is software progress after ASARA is deployed compared to today?


As we've said, there’s massive uncertainty here and significant room for reasonable disagreement.

To visualise how this parameter affects the results, we can run simulations with the initial speed up equalling 2, 8, and 32:

![Graph showing AI capabilities growth over 5 years comparing different initial speed-up scenarios: 32x (green), 8x (purple), and 2x (yellow) acceleration versus recent baseline progress (gray)](https://images.ctfassets.net/4owxfjx3z3if/77yGNNb2xh2CJFx9qEncsl/3afdb6f4358fb7d374ce7fc939a4acfd/ai-capabilities-speedup-scenarios.png?w=3840&q=75&fm=webp)

Simulations of the software intelligence explosion with different values for the Initial speed-up parameter.


In the model, if the initial speed is twice as fast then the whole software intelligence explosion happens twice as fast and the maximum pace of progress is twice as fast.

### Returns to software R&D, rrr

_**On our median guess for returns to software R&D, progress initially gets faster over time but then starts slowing down after training efficiency improves by a few OOMs.**_

After the initial speed-up from deploying ASARA, will software progress become faster or slower over time?

This depends on the model parameter rrr.

If r<1r < 1r<1, then software progress will slow down over time. If r=1r = 1r=1, software progress will remain at the same exponential rate. If r>1r>1r>1, software progress will speed up over time. (See [Eth & Davidson (2025)](https://www.forethought.org/research/will-ai-r-and-d-automation-cause-a-software-intelligence-explosion#article) for explanation.)

Luckily, the value of rrr can be studied empirically. rrr is the answer to the following question:

**When (cumulative[9](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-9)) cognitive research inputs double, how many times does software double[10](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-10)?**

We can study this question by observing how many times software has doubled each time the human researcher population has doubled.

| What does it mean for “software” to double?<br>A simple way of thinking about this is that software doubles when you can run twice as many parallel copies of your AI with the same compute.<br>But software improvements don’t just improve runtime efficiency: they also improve _capabilities_ and _thinking speed_. We translate such improvements to an equivalent increase in parallel copies. So if some capability improvement ccc increases the pace of AI progress by the same amount as doubling the number of parallel copies, we say that ccc doubled software.<br>In practice, this means we’ll need to make some speculative assumptions about how to translate capability improvements into an equivalently-useful increase in parallel copies. For an analysis which considers only runtime efficiency improvements, see [this](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#returns-to-software-rd-efficiency-improvements-only) appendix. |
| --- |

Box 1: What does it mean for “software” to double?

The best quality data on this question is Epoch’s [analysis](https://arxiv.org/abs/2405.10494) of computer vision training efficiency. They estimate **r=∼1.4\\boldsymbol{r = \\;\\sim 1.4}r=∼1.4**
: every time the researcher population doubled, training efficiency doubled 1.4 times.[11](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-11) We can use this as a starting point, and then make various adjustments:

- **Upwards for improving capabilities.** Improving training efficiency improves capabilities, as you can train a model with more effective compute. Imagine we use a 2X training efficiency gain to train a model with twice as much effective compute. How many times would that double “software”? (I.e., how many doublings of parallel copies would be equally useful?) There are various sources of evidence here:[12](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-12) toy ML experiments suggest the answer is ~1.7; human productivity studies suggest the answer is ~2.5. We put more weight on the former, so we'll estimate 2. This doubles our median estimate to r=∼2.8(1.4∗2)\\boldsymbol{r = \\, \\sim 2.8} \\; (1.4 \* 2)r=∼2.8(1.4∗2).

- **Upwards for post-training enhancements.** So far, we’ve only considered pre-training improvements. But post-training enhancements like fine-tuning, scaffolding, and prompting also improve capabilities (o1 was developed using such techniques!). These can allow faster thinking, which could be a big factor. But there might also be strong diminishing returns to post-training enhancements holding base models fixed. We'll adjust our median estimate up from 2.8 to r=∼4(2.8∗1.45)\\boldsymbol{r = \\; \\sim 4} \\; (2.8 \* 1.45)r=∼4(2.8∗1.45).

- **Downwards for less growth in compute for experiments.** Today, rising compute means we can run increasing numbers of GPT-3-sized experiments each year. This helps drive software progress. But compute isn’t growing in our scenario. That might mean that returns to additional cognitive labour diminish more steeply. On the other hand, the most important experiments are ones that use similar amounts of compute to training a SOTA model. Rising compute hasn't actually increased the number of these experiments we can run, as rising compute increases the training compute required for these SOTA models. And experiments are much less of a bottleneck for post-training enhancements. But this still reduces our median estimate down to r=∼3\\boldsymbol{r = \\; \\sim 3}r=∼3. (See [Eth and Davidson (2025)](https://www.forethought.org/research/will-ai-r-and-d-automation-cause-a-software-intelligence-explosion) for more discussion.)

- **Downwards for fixed scale of hardware.** In recent years, the scale of hardware available to researchers has increased massively. Researchers could invent new algorithms that only work at the new hardware scales for which no one had previously tried to to develop algorithms. Researchers may have been plucking low-hanging fruit for each new scale of hardware. But in the software intelligence explosions we're considering, this won’t be possible because the hardware scale will be fixed. OAI [estimate](https://arxiv.org/abs/2005.04305) ImageNet efficiency via a method that accounts for this (by focussing on a fixed capability level),[13](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-13) and find a 16-month doubling time, as compared with Epoch’s 9-month doubling time. This reduces our estimate down to r=∼1.7(3∗9/16)\\boldsymbol{r = \\; \\sim 1.7} \\; (3 \* 9/16)r=∼1.7(3∗9/16)

- **Downwards for the returns to software R&D becoming worse over time.** In most fields, returns diminish more steeply than in software R&D.[14](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-14) So perhaps software will tend to become more like the average field over time. To estimate the size of this effect, we can take our estimate that software is ~10 OOMs from effective limits (discussed [below](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#distance-to-the-effective-limits-to-software)), and assume that for each OOM increase in software, rrr falls by a constant amount, reaching zero once effective limits are reached. If r=1.7r = 1.7r=1.7, then this implies that rrr reduces by 0.17 for each OOM. Epoch estimates that pre-training algorithmic improvements are growing by an OOM every ~2 years, which would imply a reduction in rrr of 0.51(3∗0.17)0.51 \\; (3 \* 0.17)0.51(3∗0.17) by 2030. This reduces our median estimate to r=∼1.2(1.7−0.5)\\boldsymbol{r = \\; \\sim 1.2} \\; (1.7 - 0.5)r=∼1.2(1.7−0.5).


Overall, our median estimate of rrr is 1.2. We use a log-uniform distribution with the bounds 3X higher and lower (0.4 to 3.6).

![Log-uniform probability distribution for returns to software R&D parameter (r), showing equal probability density from 0.4 to 3.6 with median at 1.2, determining whether AI progress accelerates or decelerates](https://images.ctfassets.net/4owxfjx3z3if/1Dty1nWjqvRZ0EClXdaU9Z/5c22a042a50699f9b3fe72a4bf5dfbd2/returns-software-rd-distribution.png?w=3840&q=75&fm=webp)


To visualise how this parameter affects the results, we can run simulations with different values of rrr.

![Graph showing AI capabilities growth over 3 years comparing different returns to software R&D values: r=2.4 (green) shows rapid exponential growth, r=1.2 (purple) shows moderate acceleration, r=0.6 (yellow) shows deceleration versus baseline (gray)](https://images.ctfassets.net/4owxfjx3z3if/5UpgdR04oJ9pev9C0zHwc3/7f2009b8baa81742a6f9d6655ebe6db2/ai-capabilities-returns-rd-scenarios.png?w=3840&q=75&fm=webp)


Simulations of the software intelligence explosion with different values for the returns to software R&D, rrr.

Once rrr falls below 1, progress starts slowing. When rrr is higher, software progress accelerates more quickly _and_ it accelerates for longer (because software advances further before rrr falls below 1).

Also, when rrr starts higher, effective limits are approached more rapidly and so rrr itself falls more rapidly.

![Graph showing how returns to software R&D parameter (r) declines over time as AI approaches effective limits: high initial r values (2.4, green) drop rapidly to zero, moderate values (1.2, purple) decline gradually, low values (0.6, yellow) remain stable](https://images.ctfassets.net/4owxfjx3z3if/4G1D62bhEy67YWgEsmhj6o/16d71ff7c8e81752fcbe5d6e96fe3391/r-parameter-decay-over-time.png?w=3840&q=75&fm=webp)


Simulations of the path of returns to software R&D, rrr. rrr falls over time as software approaches effective limits.

### Distance to the effective limits to software

_**We estimate that, when we train ASARA, software will be 6-16 OOMs from effective limits. This is equivalent to 6-16 years worth of AI progress (at recent rates) before capping out.**_

Software cannot keep improving forever. It will never be possible to get the cognitive performance of a top human expert using the computational power of a basic calculator. Eventually we hit what we will refer to as the “effective limits” of software.

How big is the gap between the software we’ll have when we develop ASARA and these effective limits? We'll focus on training efficiency. First we'll estimate how much more efficient human learning might be than ASARA’s training. Then we'll estimate how far human learning might be from effective limits.

#### Gap from ASARA to human learning

Human lifetime learning is estimated to take [1e24 FLOP](https://docs.google.com/document/d/1IJ6Sr-gPeXdSJugFulwIpvavc0atjHGM82QjIfUSBGQ/edit?tab=t.0#heading=h.87mp14r9lgsj).[15](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-15) As a very toy calculation, let’s assume that ASARA is trained with 1e28 FLOP[16](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-16) and that at runtime it matches a human expert on a per compute basis.[17](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-17) This means ASARA is 4 OOMs less training efficient than human lifetime learning.[18](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-18)

There’s a lot of uncertainty here from the training FLOP for ASARA and the compute used by the human brain, so let’s say ASARA’s training is 2-6 OOMs less efficient than human lifetime learning.

#### Gap from human learning to effective limits

But human lifetime learning is not at the limit of learning efficiency. There is room for significant improvement to the _data_ used to train the brain, and to the brain’s _learning algorithm_.

Improvements to the _data_ used in human learning:

- **Not enough data – the brain is severely “undertrained”.** Chinchilla optimal scaling suggests that models should be trained on ~20X as many tokens as they have parameters. On this account, the human brain is severely “undertrained”: it has maybe ~1e14 “parameters” (synapses) but only processes ~1e9 “data points” (1 data point per second for 30 years) during lifetime learning. If the Chinchilla scaling laws applied to the brain, then you could train a model with the same performance as the brain but with 4-5 OOMs less training compute – see [this appendix](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#distance-to-effective-limits-human-learning). Of course, the brain architecture might have a completely different optimal scaling to Chinchilla. But it is very plausible that the brain is significantly undertrained due to hard biological constraints: organisms don’t live long enough to keep learning for 1000s of years! We'll call this **10-100,000X**.

- **Low fraction of data is relevant.** Take a human ML expert. They only spend a small fraction of their time doing focussed learning that is relevant to doing AI R&D (at most 8 hours a day on average, and plausibly much less given the time spent on poetry, history, etc). This seems like a factor of **at least 3-10X**.[19](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-19)

- **Data quality could be much higher.** Even when humans are learning relevant material, the _quality_ of the data they would be processing is far from optimal. When an outstanding communicator puts significant effort into organising and presenting content, human learning can be much more efficient. During the software intelligence explosion AI could curate better data sets still, including demonstrations of significantly superhuman performance. It seems like this would be **at least 3X and plausibly 300X**.


Improvements to the brain algorithm:

- **Brain algorithms must satisfy physical constraints.**

  - Firstly, the human brain is spread out over three dimensions, communications from one part of the brain to the other must physically ‘travel’ this distance, and the speed of communication is fairly slow. This constrains the design space of brain algorithms.

  - Secondly, the _brain cannot do weight sharing_ (where the same numerical ‘weights’ are used in different parts of the network), which is essential to transformers.

  - Thirdly, synaptic updates are _local_, just depending on nearby synapses, whereas AI can implement non-local learning rules like stochastic gradient descent where updates are instantaneously propagated throughout the entire network.

  - AI algorithms will relax these constraints. On the other hand, the hardware first used to train AGI will have different constraints – e.g. around memory access. Also, evolution _coevolved_ the brain’s hardware and software, which won’t be possible during a software intelligence explosion.

  - It’s hard to know how significant this will be overall, maybe **3-100X**.


- **Significant variation between humans.** There is significant variation in cognitive abilities between different humans. It may be possible to significantly increase human intelligence (e.g. by >100 IQ points) just by combining together all the best mutations and making more of the same kinds of changes that drive human variation. This might be another **3-10X**.[20](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-20)

- **Fundamental improvements in the brain’s learning algorithm.** Evolution is a blind search process and does not always find the optimal solutions to problems – e.g. giraffes have a [nerve](https://www.mcgill.ca/oss/article/student-contributors-did-you-know-general-science/unintelligent-design-recurrent-laryngeal-nerve) that loops up and down their neck only to end up right next to where it started. AI could write _vastly_ more complicated learning algorithms than the human brain algorithm, which is encoded within the human genome and so cannot exceed [~1e9 bits](https://en.wikipedia.org/wiki/Human_genome#Information_content). In the limit, AI could hard-code knowledge [GOFAI](https://en.wikipedia.org/wiki/GOFAI)-style rather than learning it. Again, it’s hard to know how big this will be. Maybe **3-30X**.

- **Coordination.** Humans must coordinate via language, which is slow and imperfect. By contrast, AIs could potentially communicate via multidimensional vectors and copies could share their activations with one another. This might significantly raise the collective capabilities achievable by large teams. Maybe **3-10X**.


Overall, the additional learning efficiency gains from these sources suggest that effective limits are 4 - 14 OOMs above the human brain. The high end seems extremely high, and we think there’s some risk of double counting some of the gains here in the different buckets, so we will bring down our high end to 10 OOMs. We’re interpreting these OOMs as up limits _upwards_ (increasing capabilities with fixed training compute) not as the limits _downwards_ (reducing training compute but holding capabilities constant).[21](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-21)

So ASARA has room for 2 - 6 OOMs of training efficiency improvements before reaching the efficiency of the human lifetime learning, and a further 4 - 10 OOMs before reaching effective limits, for a total of **6 - 16 OOMs**.

One reason for scepticism here is that these gains in training efficiency would be _much bigger_ than anything we’ve seen historically. Epoch [report](https://arxiv.org/pdf/2403.05812) s the training efficiency for GPT-2 increasing by 2 OOMs in a three year period, but doesn't find examples of much bigger gains over any time period. On the other hand, some of the factors listed are plausibly even bigger than our upper estimate, e.g. “must satisfy physical constraints” and "fundamental improvements”.

In recent years, effective training compute has risen by about [10X per year](https://epoch.ai/trends). So the model makes the assumption that after ASARA there could be **6 - 16 years of AI progress**, at the rate of progress seen in recent years, before software hits effective limits.

![Uniform probability distribution for distance to effective limits of AI software, showing equal likelihood across 6-16 years of progress at recent rates before reaching fundamental efficiency constraints](https://images.ctfassets.net/4owxfjx3z3if/53mRowE4luEAiRvW2hQAEN/93562665c465925c82aacde4243e28e5/distance-effective-limits-distribution.png?w=3840&q=75&fm=webp)

When we train ASARA, how far will software be from effective limits?


To visualise how this parameter affects the results, we can run simulations with different limits.

![Graph showing AI capabilities growth over 4 years comparing different distances to effective limits: 14 years (green) enables highest growth, 10.5 years (purple) shows moderate plateau, 7 years (yellow) shows earlier saturation versus baseline (gray)](https://images.ctfassets.net/4owxfjx3z3if/5llo6E2FApLvriwNCDnGne/cde286c59becaa806f9866b3a906f10c/ai-capabilities-effective-limits-scenarios.png?w=3840&q=75&fm=webp)

Simulations of the software explosion with difference values for the distance to effective limits (measured in years of AI progress at 2020-4 rates).


When effective limits are further away, software progress accelerates for longer and plateaus at a higher level.

### Diminishing returns to parallel labour

_Whether_ AI progress accelerates vs decelerates depends on the parameter rrr. But _how quickly_ it accelerates/decelerates also depends on another parameter, the diminishing returns to parallel labour ppp.

The meaning of ppp is: if you instantaneously doubled the amount of parallel cognitive labour directed towards software R&D, how many times would the pace of software progress double?

As discussed above, p=λαp = \\lambda \\alphap=λα.

- λ\\lambdaλ captures diminishing returns to parallel efforts – nine women can’t make a baby in a month! We use λ=0.6λ=0.6λ=0.6.[22](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-22)

- α\\alphaα captures the relative importance of cognitive labour inputs to software R&D, as contrasted with inputs of compute for experiments. We assume the share of software progress that’s attributable to cognitive labour is 0.5.


So our median estimate is p=0.6∗0.5=0.3p = 0.6\*0.5 = 0.3p=0.6∗0.5=0.3.

We use a log-uniform distribution over ppp from 0.150.150.15 to 0.60.60.6.

## Summary of parameter assumptions

The Monte Carlo samples four parameters from, three from log-uniform distributions and one from a uniform distribution (distance to effective limits).

|  | **Lower bound** | **Median** | **Upper bound** |
| --- | --- | --- | --- |
| **Initial speed-up** in the pace of software progress due to ASARA | 2 | 8 | 32 |
| **Returns to software R&D**, rrr | 0.4 | 1.2 | 3.6 |
| **Distance to effective limits** on software (in units of years of progress) | 6 | 11 | 16 |
| **Diminishing returns to parallel labour**, ppp | 0.15 | 0.3 | 0.6 |

| Recall we derive our model from the following law of motion:<br>S˙(L,C)=a\[R(L,C)\]λS1−β(Software L.O.M)\\dot{S}(L,C) = a\[R(L,C)\]^{\\lambda} S^{1- \\beta} \\qquad\\qquad\\qquad\\; \\text{(Software L.O.M)}S˙(L,C)=a\[R(L,C)\]λS1−β(Software L.O.M)<br>R(L,C)=(bL)α(cC)1−α(Research Input)R(L,C) = (bL)^{\\alpha}(cC)^{1- \\alpha} \\qquad\\qquad\\qquad\\qquad \\text{(Research Input)}R(L,C)=(bL)α(cC)1−α(Research Input)<br>We define p=λαp = \\lambda \\alphap=λα and r=λα/βr = \\lambda \\alpha / \\betar=λα/β.<br>Our median estimates of ppp and rrr correspond to α=0.5\\alpha=0.5α=0.5, λ=0.6\\lambda=0.6λ=0.6, β=0.25\\beta=0.25β=0.25.<br>Note that we independently sample ppp and rrr; we don’t sample the underlying λ\\lambdaλ, α\\alphaα, and β\\betaβ – we discuss this choice in an [appendix](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#objection-to-our-sampling-procedure). |
| --- |

Box 2: What do our assumptions imply about the values of λ\\lambdaλ, α\\alphaα, and β\\betaβ?

You can change all of these assumptions in the [online tool](https://accelerated-ai-progress.streamlit.app/).

## Results

| **Years of progress** | **Compressed into ≤1 year** | **Compressed into ≤4 months** |
| --- | --- | --- |
| ≥3 years | 57% | 41% |
| ≥10 years | 18% | 12% |

![Probability curve for retraining model variant showing likelihood of compressing AI progress into one year: 57% chance for 3+ years, 18% chance for 10+ years, with declining probability for more dramatic compression](https://images.ctfassets.net/4owxfjx3z3if/Wn5YS1bYQKyyBt1DFIAiF/897826b2a263b552d44d0546dd2fe7b3/retraining-model-probability-results.png?w=3840&q=75&fm=webp)


It goes without saying that this is all very rough and at most one significant figure should be taken seriously.

The appendix contains the results for two variants of the model:

- **Retraining cost.** This variant models the fact that we’ll need to train new generations of AI from scratch during the intelligence explosion and, if progress is accelerating, those training runs will have to become quicker over time. [More](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#retraining-time).

- **Gradual boost.** This variant models AI systems intermediate between today’s AI and ASARA. It assumes AI gradually accelerates software progress more and more over time. [More.](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#gradual-boost-from-pre-asara-systems)


Both variants are consistent with the bottom line that **the software intelligence explosion will probably compress >3 years of AI progress into 1 year, but is somewhat unlikely to compress >10 years into 1 year**.

_You can choose your own inputs to the model [here](https://accelerated-ai-progress.streamlit.app/)._

## Limitations and caveats

**We're not modelling the actual mechanics of the software intelligence explosion.** For example, there’s no gears-level modelling of how synthetic data generation[23](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-23) might work or what specific processes might drive very rapid progress. We don’t even separate out post-training from pre-training improvements, or capability gains from inference gains. Instead we attempt to do a high-level extrapolation from the patterns in _inputs_ and _outputs_ to AI software R&D, considered holistically. As far as we can tell, this doesn’t bias the results in a particular direction, but the exercise is very speculative and uncertain.

Similarly, we don’t model specific AI capabilities but instead represent AI capabilities as an abstract scalar, corresponding to how capable they are at AI R&D.

**Significant low-hanging fruit may be plucked before ASARA.** If ASARA is good enough to speed up software progress by 30X, earlier systems may already have sped it up by 10X. By the time ASARA is developed, the earlier systems would have plucked the low-hanging fruit for improving software. Software would be closer to effective limits and returns to software R&D would be lower (lower rrr). So the simulation will overestimate the size of the software intelligence explosion.

How could we do better? The [gradual boost model variant](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#gradual-boost-from-pre-asara-systems) does just this by modelling the gradual development of ASARA over time and modelling the software progress that happens before ASARA is developed. Our high-level bottom line holds true: the software intelligence explosion will probably compress 3 years of AI progress into 1 year, but is somewhat unlikely to compress 10 years into 1 year.

**Assuming the historical scaling of capabilities with compute continues.** We implicitly assume that, during the software intelligence explosion, doubling the effective compute used to develop AI continues to improve capabilities as much as it has done historically. This is arguably an aggressive assumption, as it may be necessary to spend significant compute on generating high-quality data (which wasn’t needed historically). This could also be conservative if we find new architectures or algorithms with more favourable scaling properties than historical scaling.

- We make this assumption when [estimating the returns to software R&D rrr](https://docs.google.com/document/d/1LSfKB54gs2t6-T0fzCaKqxtszUD2V2iUG5CdqFXpBsk/edit?tab=t.myepkk2wclag#heading=h.v156a5eli5jh), in particular when assessing the benefits of training models with better capabilities.


**“Garbage in, garbage out”.** We’ve done our best to estimate the model parameters fairly, but there are massive uncertainties in all of them. This flows right through to the results. The assumption about effective limits is especially worth calling out in this regard.

- You can choose your own inputs [here](https://accelerated-ai-progress.streamlit.app/)!


_Thanks to Ryan Greenblatt, Eli Lifland, Max Daniel, Raymond Douglas, Ashwin Acharya, Owen Cotton-Barratt, Max Dalton, William MacAskill, Fin Moorhouse, Ben Todd, Lizka Vaintrob and others for review and feedback. Thanks especially to Rose Hadshar for help with the writing._

## Appendices

### Derivation of our pseudo code from the semi-endogenous growth model

We start with the following environment (described in the main text):

S˙(L,C)=a\[R(L,C)\]λS1−β(Software L.O.M)\\, \\dot{S}(L, C) = a\[R(L, C)\]^{\\lambda} S^{1-\\beta} \\qquad\\qquad\\; \\text{(Software L.O.M)}S˙(L,C)=a\[R(L,C)\]λS1−β(Software L.O.M)

R(L,C)=(bL)α(cC)1−α(Research Input)R(L, C) = (bL)^{\\alpha}(cC)^{1-\\alpha} \\qquad\\qquad\\qquad \\text{(Research Input)}R(L,C)=(bL)α(cC)1−α(Research Input)

L=dS(Cognitive Labour)\\qquad\\;\\;\\, L = dS \\qquad\\qquad\\qquad\\qquad\\quad\\;\\;\\, \\text{(Cognitive Labour)}L=dS(Cognitive Labour)

Combining these three expressions we get the growth rate of software, as a function of software level and compute:

gS(S,C)=Sλα−βCλ(1−α)×a(bd)λαcλ(1−α)g\_S(S,C) \\;\\; = \\;\\; S^{\\lambda \\alpha - \\beta} C^{\\lambda(1-\\alpha)} \\times a(bd)^{\\lambda \\alpha} c^{\\lambda(1-\\alpha)}gS​(S,C)=Sλα−βCλ(1−α)×a(bd)λαcλ(1−α)

=Sλα−βCλ(1−α)×Constant\\qquad\\qquad\\;\\, = \\;\\; S^{\\lambda \\alpha - \\beta} C^{\\lambda(1-\\alpha)} \\times \\text{Constant}=Sλα−βCλ(1−α)×Constant

From here, the time it takes for software to double is given by

Doubling Time(S,C)=log⁡(2)gS(S,C)\\text{Doubling Time} (S,C) = \\frac{\\log(2)}{g\_S(S,C)}Doubling Time(S,C)=gS​(S,C)log(2)​

Next, if we want to express the doubling time under software level 2S2S2S in terms of the doubling time for software under software level SSS, we can divide expressions:

Doubling Time(2S,C)Doubling Time(S,C)=gS(S,C)gS(2S,C)\\quad\\;\\; \\displaystyle \\frac{\\text{Doubling Time}(2S,C)}{\\text{Doubling Time}(S,C)} = \\frac{g\_S(S,C)}{g\_S(2S,C)}Doubling Time(S,C)Doubling Time(2S,C)​=gS​(2S,C)gS​(S,C)​

⟹Doubling Time(2S,C)=Doubling Time(S,C)×2β−λα\\Longrightarrow \\text{Doubling Time}(2S,C) = \\text{Doubling Time}(S,C) \\times 2^{\\beta - \\lambda \\alpha}⟹Doubling Time(2S,C)=Doubling Time(S,C)×2β−λα

So we can see that after a doubling of software, the time it takes to complete the next doubling halves λα−β\\lambda \\alpha - \\betaλα−β times. To map this expression to the parameters in the rest of this analysis, we define ppp and rrr as in the main text:

p:=λαp := \\lambda \\alphap:=λα

r:=λαβr := \\dfrac{\\lambda \\alpha}{\\beta}r:=βλα​

And therefore, to get the doubling time expression in the [pseudo code](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#pseudocode) note β−λα=p(r−1−1)\\beta - \\lambda \\alpha \\; = \\; p (r^{-1} - 1)β−λα=p(r−1−1), therefore

Doubling Time(2S,C)=Doubling Time(S,C)×2p(1r−1)\\text{Doubling Time}(2S,C) = \\text{Doubling Time}(S,C) \\times 2^{p(\\frac{1}{r} - 1)}Doubling Time(2S,C)=Doubling Time(S,C)×2p(r1​−1)

Therefore, so long as we know the initial doubling time of software and ppp and rrr for each time period, we can chain together doubling times to calculate a path of software.

### Additional model assumptions

In addition to the [pseudo code](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#pseudocode), the results reported in the piece are also determined by three additional assumptions:

1. AI software has recently been doubling every 3 months ( [more](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#ai-software-has-recently-been-doubling-every-3-months)).

2. Half of AI progress is due to software and half is due to compute ( [more](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#calculating-the-summary-statistics)).

3. Our sampling procedure for the Monte Carlo ( [more](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#objection-to-our-sampling-procedure)).


#### AI software has recently been doubling every 3 months

A model parameter specifies the _initial speed-up_ in software progress from deploying ASARA. But we also need to make an assumption about how fast AI software has progressed recently. Then we can calculate:

Initial software doubling time after ASARA=recent software doubling timeinitial speed-up\\text{Initial software doubling time after ASARA} = \\frac{\\text{recent software doubling time}}{\\text{initial speed-up}}Initial software doubling time after ASARA=initial speed-uprecent software doubling time​

We assume that recently software has doubled every 3 months.

Why 3 months? [Epoch estimates](https://epoch.ai/trends) that training efficiency doubles every 8 or 9 months, but that doesn't include post-training enhancements which would make things faster. So we adjust down to 6 months. This is the doubling time of _training efficiency_ – the training compute needed to achieve some capability level.

But the simulation measures “software” in units of parallel labour. A doubling of software is any software improvement as useful as an improvement that doubles the number of parallel copies you can run.

The main body [argues](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#returns-to-software-rd-) that, measured in these units, software doubles more quickly than training efficiency because better training efficiency allows you to access better capabilities, and this is more valuable than having twice as many parallel copies. Based on this consideration, we adjust the 6 months down to 3 months.

#### Calculating the summary statistics

The simulation spits out a trajectory for AI software progress over time. From that we can calculate “there was a 1 year period where we had 10 years of **software** progress at recent rates of progress”.

But our results report how many years of **overall** AI progress we get in each year. So we must make an additional assumption about how the recent pace of _software_ progress compares to the recent rate of _overall_ AI progress. **We assume that half of recent progress has been driven by compute and the other half by software.**

To illustrate this assumption, it allows the following inference:

There was a 1 year period where we had 10 years of _software_ progress → There was a 1 year period where we had 5 years of _overall_ AI progress.

You can change this assumption in the [online tool](https://accelerated-ai-progress.streamlit.app/).

#### Objection to our sampling procedure

It might seem ill-advised to independently sample r=αλβr = \\frac{\\alpha \\lambda}{\\beta}r=βαλ​ and p=αλp = \\alpha \\lambdap=αλ. Should we not instead sample β\\betaβ and αλ\\alpha \\lambdaαλ? After all, these are the more fundamental inputs that determine the model behaviour. For example, we will sample αλ\\alpha \\lambdaαλ holding rrr fixed – this means that a higher value for αλ\\alpha \\lambdaαλ will change the (implicit) value of β\\betaβ.

We tentatively think our sampling procedure is appropriate given our epistemic position. The best evidence we have to calibrate the model is evidence about rrr. This comes from observing the ratio between the growth rate of inputs and the growth rate of outputs to AI R&D: r=goutputsginputsr = \\frac{g\_{\\text{outputs}}}{g\_{\\text{inputs}}}r=ginputs​goutputs​​. Given our evidence on rrr, it is the case that from our epistemic position it is appropriate that a higher estimate of αλ\\alpha \\lambdaαλ should change our estimate of β\\betaβ.

To be concrete, suppose our evidence tells us that r=2r = 2r=2. Then we sample from our distribution over αλ\\alpha \\lambdaαλ. If we sample a high value, it is appropriate for us to assume that β\\betaβ is also high, so that our assumption about αλ\\alpha \\lambdaαλ remains consistent with our evidence about rrr.

A more sophisticated approach here is surely possible. But our model is intended as a glorified BOTEC and we don’t expect additional sophistication would significantly affect the bottom line. And our remaining uncertainty about the bottom line stems much more from uncertainty about the right parameter values than from uncertainty about the sampling procedure.

### Variants of the model

We explore two variants of the model that make it more realistic in certain respects.

#### Retraining time

We have accounted for the time to train new AI systems in our estimate of the _initial_ speed of software progress. But retraining also affects how the pace of AI progress should change over time.

Let’s say that AI progress requires two steps: improving software and retraining.

![Flowchart showing retraining model cycle: improved AI software leads to retraining AI, which produces better AI, creating feedback loop for continued improvement](https://images.ctfassets.net/4owxfjx3z3if/1Qg5rfXrzyUPMJW3RvSJbi/1d11052f10b9098ea975c50496fa6140/retraining-cycle-diagram.png?w=3840&q=75&fm=webp)


As software progress becomes very fast, retraining will become a bottleneck. To avoid this, some of your software improvements can be “spent” on reducing the duration of training rather than on improving capabilities. As a result of this expenditure, the pace of AI progress accelerates more slowly. (An inverse argument shows that the pace of AI progress also _decelerates_ more slowly, as you can expand the time for training as progress slows.)

A simple way to model this is to assume that **each time the pace of software progress doubles, the duration of training must halve**. Software progress and training become faster in tandem.

How can we incorporate this into the model? Suppose that the model previously stated that software doubled NNN times before the pace of software progress doubled. We should increase this to N+1N+1N+1. The extra software doubling is spent on reducing the duration of training. Our rough median estimate for NNN is 5, as argued in [this appendix](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#how-the-speed-of-software-progress-changes-over-time).

![Diagram showing retraining model timing: total time to create next generation AI includes both software improvement and retraining phases, with accelerating progress requiring faster retraining to avoid bottlenecks](https://images.ctfassets.net/4owxfjx3z3if/40gkSK4CZA9Lp78dUmubLl/8faccff8b69b088f70fdb5f3ffb3b01e/retraining-time-allocation-diagram.png?w=3840&q=75&fm=webp)


Specifically, we adjust the line of code that describes how software progress changes each time software doubles:

Doubling-Timei+1=2p(1ri−1)×Doubling-Timei\\text{Doubling-Time}\_{i+1} \\;\\; = \\;\\; 2^{p(\\frac{1}{r\_i} - 1)} \\times \\text{Doubling-Time}\_iDoubling-Timei+1​=2p(ri​1​−1)×Doubling-Timei​

The exponent on 2 here is the reciprocal of NNN: p((1/r)−1)=1/Np((1/r) - 1) = 1/Np((1/r)−1)=1/N. So we replace this exponent with

1N+1=p(1r−1)1+p(1r−1)\\displaystyle \\frac{1}{N+1} = \\frac{p(\\frac{1}{r}-1)}{1+p(\\frac{1}{r}-1)}N+11​=1+p(r1​−1)p(r1​−1)​

This analysis assumed that software was accelerating over time – N>0N>0N>0, p((1/r)−1)>0p((1/r) - 1)>0p((1/r)−1)>0. Repeating the argument for the case where software is decelerating – N<0N<0N<0, p((1/r)−1)<0p((1/r) - 1)<0p((1/r)−1)<0 – yields p((1/r)−1)(1−(p((1/r)−1)))\\frac{p((1/r) - 1)}{(1 - (p((1/r) - 1)))}(1−(p((1/r)−1)))p((1/r)−1)​. Therefore the correct exponent in both cases is λ((1/r)−1)(1+∣λ((1/r)−1)∣)\\frac{\\lambda((1/r) - 1)}{\\left(1 + \|\\lambda((1/r) - 1)\|\\right)}(1+∣λ((1/r)−1)∣)λ((1/r)−1)​.

We rerun the analysis with this new exponent and find that the results do not change significantly.

|  |  |  |  |  |
| --- | --- | --- | --- | --- |
| **Years of progress** | **Compressed into ≤1 year** | **Compressed into ≤4 months** |  |  |
|  | RT | No RT | RT | No RT |
| ≥3 years | 57% | 56% | 41% | 41% |
| ≥10 years | 19% | 17% | 14% | 12% |

![Graph showing probability of compressing at least X years into one year. Declining orange curve from 1.0 to 0.0 probability over 0-20 years compressed. Vertical lines mark 3 years (57% probability) and 10 years (19% probability).](https://images.ctfassets.net/4owxfjx3z3if/5Oe1Dkx64NphOVLzRg225E/b7a18c707d050de0b9b4195806540c3c/time_compression_probability_curve.png?w=3840&q=75&fm=webp)


See [here](https://www.forethought.org/research/will-the-need-to-retrain-ai-models) for more analysis of how retraining affects the dynamics of the software intelligence explosion.

#### Gradual boost (from pre-ASARA systems)

In the main results we assume ASARA boosts the pace of software progress by 2-32x (median 8x) and the simulation starts from when this boost is first felt. In the ‘compute growth’ scenario we assume that this boost ‘ramps up’ (exponentially) over 5 years, mapping to the time frame over which we expect compute for AI development could continue to grow rapidly.

![Graph showing acceleration factor f(t) growing linearly from 0.1 to 8 over 5 years in gradual boost model, representing gradual ramp-up from minimal AI assistance to full ASARA capabilities](https://images.ctfassets.net/4owxfjx3z3if/76sjDeeO4UBnXYoP0jehXK/65a858eb2f487042e0b08041478e7cac/acceleration-factor-gradual-boost.png?w=3840&q=75&fm=webp)


In the simulation, the initial boost to research productivity from deployment of AI is an additional 10% on top of the usual rate of software progress. The boost then grows linearly over time until it reaches the sampled maximum value (between 2 and 32).

To implement this in the model, we assume that the boost in each time period originates from compute growth, which grows at an exogenous (and exponential) rate until it reaches a ceiling. We assume this ceiling occurs after 12 doublings of compute (or a 4096× increase relative to the initial compute level) which occurs after five years from the start time of the model.

Softwarei+1=2×Softwarei\\qquad\\;\\; \\text{Software}\_{i+1} = 2 \\times \\text{Software}\_iSoftwarei+1​=2×Softwarei​

fi+1=1+f0+(fmax−f0)×min⁡{Timei,TimeBoost End}\\qquad\\qquad\\qquad f\_{i+1} = 1 + f\_0 + (f\_{\\text{max}} - f\_0) \\times \\min\\{\\text{Time}\_i, \\text{Time}\_{\\text{Boost End}}\\}fi+1​=1+f0​+(fmax​−f0​)×min{Timei​,TimeBoost End​}

Timei+1=Timei+Doubling-Timei\\qquad\\qquad\\; \\text{Time}\_{i+1} = \\text{Time}\_i + \\text{Doubling-Time}\_iTimei+1​=Timei​+Doubling-Timei​

Doubling-Timei+1=2p(1ri−1)×Doubling-Timei×fifi+1\\text{Doubling-Time}\_{i+1} = 2^{p(\\frac{1}{r\_i} -1)} \\times \\text{Doubling-Time}\_i \\times \\frac{f\_i}{f\_i +1}Doubling-Timei+1​=2p(ri​1​−1)×Doubling-Timei​×fi​+1fi​​

ri+1=ri−r0Doublings-Till-CeilingSoftware\\qquad\\qquad\\qquad\\, r\_{i+1} = r\_i - \\frac{r\_0}{\\text{Doublings-Till-Ceiling}\_{\\text{Software}}}ri+1​=ri​−Doublings-Till-CeilingSoftware​r0​​

Doubling-Time0,Doublings-Till-CeilingSoftware,\\qquad\\qquad\\qquad\\qquad\\quad\\, \\text{Doubling-Time}\_0, \\text{Doublings-Till-Ceiling}\_{\\text{Software}},Doubling-Time0​,Doublings-Till-CeilingSoftware​,

TimeBoost End,r0,p,f0,fmax, are given;\\qquad\\qquad\\qquad\\qquad\\quad\\, \\text{Time}\_{\\text{Boost End}}, r\_0, p, f\_0, f\_{\\text{max}}, \\;\\text{ are given};TimeBoost End​,r0​,p,f0​,fmax​, are given;

Time0=0,Software0=1,Compute0=1\\qquad\\qquad\\qquad\\qquad\\quad\\, \\text{Time}\_0 = 0, \\text{Software}\_0 = 1, \\text{Compute}\_0 = 1Time0​=0,Software0​=1,Compute0​=1

In the simulation it is assumed that f0=0.1f\_0 = 0.1f0​=0.1. Given exponential growth in compute, f(C)f(C)f(C) increases linearly with time until it reaches the compute ceiling, at which point fff remains at fmaxf\_{\\text{max}}fmax​.

When running this version of the simulation we increase rrr. This is because we [previously discounted rrr](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#returns-to-software-rd-) on account of software progress made in the run-up to ASARA making returns to software R&D more steep. But this simulation models the gradual ramp up to ASARA so this discount isn’t needed.

|  | **Lower bound** | **Median** | **Upper bound** |
| --- | --- | --- | --- |
| rrr in other sims | 0.4 | 1.2 | 3.6 |
| rrr in this sim | 1.7/3 = 0.57 | 1.7 | 1.7\*3 = 5.1 |

We also increase the distance to effective limits. The simulation starts at an earlier point in time when software is less advanced and further from limits. Epoch estimates that training efficiency increases by about 0.5 OOMs/year and, to include some intermediate speed up in software progress, we add 3 OOMs.

|  | **Lower bound** | **Median** | **Upper bound** |
| --- | --- | --- | --- |
| Distance to effective limits in other sims | 6\\hspace{3cm} | 11 | 16\\hspace{3cm} |
| Distance to effective limits in this sim | 9 | 14 | 19 |

Here are the results:

|  |  |  |  |  |
| --- | --- | --- | --- | --- |
| **Years of progress** | **Compressed into ≤1 year** | **Compressed into ≤4 months** |  |  |
|  | GB | No GB | GB | No GB |
| ≥3 years | 59% | 56% | 48% | 41% |
| ≥10 years | 32% | 17% | 24% | 12% |

![Probability curve for gradual boost model variant showing likelihood of compressing AI progress into one year: 59% chance for 3+ years, 32% chance for 10+ years, with declining probability for more dramatic compression](https://images.ctfassets.net/4owxfjx3z3if/4aruN7HsGlPwaNqTraucL8/0fee1324115d6525bcebc6151b230f01/gradual-boost-probability-results.png?w=3840&q=75&fm=webp)


The software intelligence explosion is _more_ dramatic, presumably because we used more aggressive parameter values for rrr and the distance to effective limits.

### Returns to software R&D: efficiency improvements only

In the main text, we include both runtime efficiency and capabilities improvements in our estimates of rrr for software progress. But the capabilities improvements are necessarily more speculative: to pin down what counts as a doubling, we need to implicitly translate capabilities improvements into equivalent improvements in runtime efficiency.

To check how robust our main estimate is to this speculative translation, we can ask what rrr is when considering only direct runtime efficiency improvements.

As above, the highest quality and most relevant estimate is Epoch’s [analysis](https://arxiv.org/abs/2405.10494) of computer vision training efficiency.[24](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-24) They estimate r=∼1.4\\boldsymbol{r = \\; \\sim 1.4}r=∼1.4 (every time the researcher population doubled, training efficiency doubled 1.4x).

Again we'll make a couple of adjustments:

- **Downwards for runtime efficiency.** To estimate returns to software improvements, we need to convert from training efficiency (the inputs) into runtime efficiency (the outputs). The logic of the [Chinchilla paper](https://arxiv.org/pdf/2203.15556) implies that increasing training efficiency by 4X will increase runtime efficiency by between 2X and 4X.[25](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-25) This means we should reduce our estimate of rrr by up to a factor of 2. We'll reduce it to r=∼1\\boldsymbol{r = \\; \\sim1}r=∼1.

- **Upwards for post-training enhancements.** Improvements to pre-training algorithms are not the only source of runtime efficiency gains. There are also techniques like: model distillation; calling smaller models for easier tasks; pruning (removing parameters from a trained model); post-training quantisation (reducing the numerical precision of the weights); more efficient caching of results and activations (especially for agents that re-read the same context multiple times). We'll increase our estimate to r=∼1−2\\boldsymbol{r = \\; \\sim 1-2}r=∼1−2.


rrr is necessarily lower when we’re considering only efficiency improvements - but it still seems fairly likely that r>1r>1r>1, even excluding capabilities improvements.

### Distance to effective limits: human learning

How much more efficient could human learning be if the brain wasn’t undertrained?

![Chinchilla scaling diagram showing human brain severely undertrained with 1e14 synapses and 1e24 FLOP learning, positioned far from optimal efficiency frontier, suggesting 4+ OOM training efficiency gains possible](https://images.ctfassets.net/4owxfjx3z3if/4bYgWa3tipGUdtEVBcgCyd/891b70c66735ecf530f7927e992b2726/chinchilla-scaling-human-brain-comparison.png?w=3840&q=75&fm=webp)

This is a naive extrapolation from the Chinchilla paper results. The blue line shows the optimal scaling path. The training FLOP and parameters for human lifetime learning is shown at the top right – the human brain is severely undertrained. The pink lines indicate how much training FLOP would produce a model with the same loss as the brain, if the model were trained optimally.


\[Thanks to Marius Hobbhahn and Daniel Kokotajlo for help with this diagram.\]

It looks like the efficiency gain is over 5 OOMs. Tamay Besiroglu wrote [code](https://colab.research.google.com/drive/1kpl6B9MHkYUwLSSleOk02pAnpSGxE25H#scrollTo=4QUHTDV0AKwT) to calculate this properly, and found that the efficiency gain was 4.5 OOMs.

### Discussion of model assumptions

#### How the returns to software R&D rrr changes over time

_**Each time software doubles, rrr decreases by a constant absolute amount; rrr reaches 0 once software hits the ceiling.**_

The returns to software R&D are represented by the parameter rrr. How does rrr change over time?

One way to think about rrr is: each time you double _cumulative cognitive inputs_ to software R&D, software doubles rrr times.[26](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fn-26)

This means that if rrr halves, the pace of software progress halves (holding the growth of cumulative inputs fixed). rrr is directly proportional to the pace of progress (holding the growth of cumulative inputs fixed).

The model assumes that:

1. By the time software reaches effective limits, r=0r = 0r=0. This means that further software progress is impossible.

2. Each time software doubles, rrr decreases by a constant absolute amount. This absolute amount is chosen to be consistent with #1.


   - So, for example: once software has advanced half the distance to effective limits in log-space, the value of rrr will have halved. Each doubling of cumulative inputs will double software half as many times as before.


**This assumption could easily be very wrong in either direction.** Returns might become steeper much more quickly, far from effective limits, perhaps because many OOMs of improvement require massive computational experiments that are not available. Alternatively, it’s possible that returns stay similar to their current value until we are within an OOM of effective limits.

#### How the speed of software progress changes over time

The math of the model states that every time software doubles, the pace of software progress doubles p(1−1/r)p(1 - 1/r)p(1−1/r) times.

Let’s assume software progress becomes faster over time (r>1)(r>1)(r>1). How quickly does it become faster?

Let’s assume r=2.5r=2.5r=2.5 (which is close to our median conditioning on r>1r>1r>1) and p=0.3p=0.3p=0.3, each time software doubles the pace of software progress doubles 0.18 times. In other words, a very rough median estimate is that, **in a software intelligence explosion, every time software doubles 5 times, the pace of software progress itself will double**.

1

Greenblatt guesses that the improvement in capability from 6 OOMs of effective compute would be the same as 8 OOMs of rank improvement within a profession. We’ll take the relevant profession to be technical employees of frontier AI companies and assume the median such expert is the 1000th best worldwide. So 8 OOMs of improvement would be 8/3 = 2.7 steps the same size as from a median expert to a top expert. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-1)

2

Minor caveat: The starting point in this example (“30,000 top-expert-level AIs thinking 30x human speed”) corresponds to a slightly higher capability level than our definition of ASARA. We define ASARA as AI that can replace _every_ employee with 30x copies running at 30x speed; if there are 1000 employees this yields 30,000 AI thinking 30x human whose average capability matches the _average_ capability of the human researchers. Rather than top-expert-level ASARA is mean-expert-level. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-2)

3

AI-2027 median forecast is 8 months from the superhuman coder to the superintelligent AI researcher (SIAR). The superhuman coder roughly corresponds to ASARA (it’s better at coding and maybe a bit worse at ideation), so AI-2027 forecasts ~8 months from ASARA to SIAR. I estimate that SIAR is roughly 6 years worth of AI progress above ASARA. ( _Explaining this estimate: The gap between the SIAR and a top human expert is defined as 2X the gap between a top human expert and a median human expert within an AGI company. That latter gap corresponds to a 1000-fold rank ordering improvement (as the median lab expert is ~1000th best in the world). So SIAR is a 1 million-fold rank ordering improvement on the top human expert. The top human expert is maybe ~100-fold rank ordering improvement on top of ASARA, which matches the mean company employee and so is better than the median. So SIAR is maybe a ~100 million-fold rank ordering improvement on ASARA. Ryan Greenblatt [estimates](https://redwoodresearch.substack.com/p/what-does-10x-ing-effective-compute?utm_source=post-email-title&publication_id=2318730&post_id=166749974&utm_campaign=email-post-title&isFreemail=true&r=jaafn&triedRedirect=true&utm_medium=email) that a 100 million-fold rank ordering corresponds to 6 OOMs of effective compute, which is roughly 6 years of recent AI progress. So, SIAR is ~6 years of AI progress above ASARA. Of course, this is all very rough!_) This means that AI-2027 forecasts 6 years of AI progress in 8 months. This is more aggressive than our median forecast, but not in the tails. AI-2027 then additionally assumes that very rapid AI progress continues so that after 5 further months AI is superintelligent in every cognitive domain (rather than just AI research, as for SIAR). If this is another 4 years of progress (at recent rates) then AI-2027 are forecasting 10 years of AI progress in 13 months, which corresponds to our ~20th percentile most aggressive scenarios. So, very roughly and tentatively, it looks like our model assigns ~20% probability to the software intelligence explosion being faster than AI-2027’s median forecast. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-3)

4

Note that [Aghion, Jones and Jones (2019](https://web.stanford.edu/~chadj/AJJ-AIandGrowth.pdf)) describe a slightly different condition for infinite technological progress in finite time: β<0\\beta<0β<0. Our finite time explosion condition is slightly different due to the fact that in our model software levels enter the research inputs function. Explosion conditions for a similar model are presented in [Davidson et al. (2025)](https://thomas-houlden.com/assets/shs_slides.pdf). [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-4)

5

In the section below where we estimate the distance to effective limits, we first estimate this in units of ‘years of AI progress at recent rates’. We convert this to Doublings-Till-Ceiling\\text{Doublings-Till-Ceiling}Doublings-Till-Ceiling by assuming AI progress has made 4 doublings of effective compute inputs per year. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-5)

6

There’s a spectrum of ASARA definitions that we could use, ranging from _weak_ (e.g. a 10X increase in the cognitive labour going into AI R&D) to _strong_ (e.g. a 100,000X increase). As AI improves, we’ll pass through the full spectrum. Both extremes of the spectrum have downsides for our purposes in this forecast. If we use a _weak_ definition, then we’ll forecast a software intelligence explosion that is initially slow and accelerates gradually over time, e.g. over multiple years, eventually still becoming very fast. But then we’ll miss the fact that _rising compute_ (which we’re not modelling) will cause the software intelligence explosion to accelerate much more quickly than our forecasts. Alternatively, we could use a _strong_ definition of ASARA. But then we’ll have only achieved ASARA by already making significant rapid software progress – i.e. a big chunk of the software intelligence explosion will have already occurred. Our definition attempts to balance these concerns. A variant of this model discussed below considers ASARA being _gradually_ developed, which avoids the need to choose one arbitrary point in the sand. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-6)

7

Of course, in fact ASARA will have very different comparative strengths and weaknesses to human researchers. We can define ASARA as AI which is as impactful on the pace of AI progress as if it replaced each human with 30 copies each thinking 30X faster. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-7)

8

They happen to calculate the speed-up from exactly the definition of ASARA we are using here: replacing each researcher with 30X copies each thinking 30X as fast. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-8)

9

In areas of technology, if you double your research workforce overnight, the level of technology won’t immediately double. So we look at how many times output (in this case, efficiency) doubles when the _cumulative_ research inputs doubles. On this definition of ‘cumulative input’, having 200 people work for a year constitutes twice as much cumulative input as having 100 people work for a year. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-9)

10

What does it mean for software to “double”? See box 1 a few paragraphs below! [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-10)

11

Epoch’s preliminary analysis indicates that the rrr value for LLMs would likely be somewhat higher. We don’t adjust upwards this, because it’s a speculative and probably minor adjustment. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-11)

12

You get the answer to this question by taking [these estimates of NNN](https://docs.google.com/document/d/1DZy1qgSal2xwDRR0wOPBroYE_RDV1_2vvhwVz4dxCVc/edit?tab=t.0#bookmark=id.eqgufka8idwl) and then adding 0.5. Why? Doubling training efficiency has two effects:

- First, you can train a model with 2X the effective compute (your compute is the same, but you’ve doubled training efficiency, so _effective_ compute is doubled too). This doubles your output-per-runtime-FLOP NNN times, as estimated in the [source](https://docs.google.com/document/d/1DZy1qgSal2xwDRR0wOPBroYE_RDV1_2vvhwVz4dxCVc/edit?tab=t.0#bookmark=id.eqgufka8idwl). This effect is equivalent to doubling runtime efficiency NNN times.

- Secondly, it also allows you to _run_ that model with sqrt(2) less compute. This literally doubles runtime efficiency 0.5 times.

- So if you double training efficiency this increases output by the same as if you’d doubled runtime efficiency N+0.5N+0.5N+0.5 times.


[↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-12)

13

This isn’t actually a fixed hardware scale, because the hardware required _decreases_ over time. A fixed hardware scale might see slightly faster improvements. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-13)

14

See [Nagy et al (2013)](https://journals.plos.org/plosone/article?id=10.1371/journal.pone.0052669) and [Bloom et al (2020)](https://web.stanford.edu/~chadj/IdeaPF.pdf). [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-14)

15

This assumes the human brain does the equivalent of 1e15 FLOP/second and lifetime learning lasts for 30 years (=1e9 seconds). [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-15)

16

This is 10X less than [Epoch’s estimate](https://epoch.ai/blog/can-ai-scaling-continue-through-2030#fnref:59) of what the biggest AI lab could achieve by the end of the decade. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-16)

17

In other words, each FLOP of computation is (on average) equally useful for doing AI R&D whether it’s done by ASARA or done in a human expert’s brain. Why make this assumption? It matches our definition as ASARA as AI that increases cognitive output for AI R&D by ~1000X. If the lab uses the same amount of compute for training ASARA as for running it (1e21 FLOP/s), and ASARA matches humans on a per-compute basis, and the [human brain uses 1e15 FLOP](https://www.openphilanthropy.org/research/how-much-computational-power-does-it-take-to-match-the-human-brain/), then the lab could run 1 million human-equivalents. If there are 1000 human researchers then they could run 30 copies of each thinking 30X as quick, matching our definition of ASARA. [See calc](https://docs.google.com/spreadsheets/d/1hh2KWYN-kuPP2yZvraxgBiv48evFCU5wmFUZ4RlkWl0/edit?usp=sharing). [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-17)

18

Thanks to Carl Shulman for this argument. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-18)

19

3X relative to humans spending 8 hours a day on focussed learning, as AI systems could do this for 24 hours a day. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-19)

20

A [rough BOTEC](https://docs.google.com/spreadsheets/d/1MFRH2A-atWr5LA1nd3UNgs0Nd-GGybqx_d7fS2rPkT0/edit?gid=0#gid=0) suggests that using 2X the computation in human lifetime learning corresponds to ~30 IQ points, so 100 IQ points would be 10X. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-20)

21

We think the downwards limits could be much smaller as, expanding downwards, you eventually hit very hard limits – it’s just not possible to train AGI with the amount of compute used in one GPT-4 forward pass. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-21)

22

We're not aware of good evidence about the value of this parameter. See discussion of this parameter [here](https://takeoffspeeds.com/playground.html) under “R&D parallelization penalty”. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-22)

23

In this model, synthetic data generation probably should be considered a technique to increase effective training compute – get better capabilities with a fixed supply of compute. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-23)

24

Epoch also [analysed](https://arxiv.org/abs/2405.10494) runtime efficiency in other domains:

- Computer chess: r=0.8r = 0.8r=0.8

- Boolean satisfiability: r=1.6r = 1.6r=1.6

- Problem solvers: r=3.5r = 3.5r=3.5

- Linear programming: r=1.1r = 1.1r=1.1


These estimates are broadly consistent with our median estimate above of r=∼1.2r = \\; \\sim 1.2r=∼1.2. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-24)

25

If we assume all efficiency improvements come from making models smaller (i.e. achieving the same performance with fewer parameters), then we’d expect training efficiency to grow twice as quickly as runtime efficiency. This conclusion is implied by a landmark 2022 [paper](https://arxiv.org/pdf/2203.15556) from DeepMind, often informally referred to as the “Chinchilla paper.” The Chinchilla paper found that when training an LLM with a fixed computational budget, it’s best to scale the size of the model and the amount of data used to train the model equally; therefore, if a model can be made ZZZ times smaller, the runtime efficiency will grow by ZZZ, while the training efficiency would grow by Z2Z^2Z2 (since training on each data point will require 1/Z1/Z1/Z as much computations, and you will only need to train on 1/Z1/Z1/Z as many data points as well). This line of reasoning, however, does not apply to efficiency improvements coming from other avenues, which in many instances will have comparable effects in speeding up training and running systems. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-25)

26

“Inputs” as defined here do not account for stepping on toes effects: having twice as many people work for the same amount of time produces twice as much input. [↩](https://www.forethought.org/research/how-quick-and-big-would-a-software-intelligence-explosion-be#user-content-fnref-26)